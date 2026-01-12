<?php

namespace App\Http\Controllers\Client;

use App\Http\Requests\Client\PickRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderDetail;
use App\Models\MonthRent;
use App\Models\Product;
use App\Models\Store;
use App\Models\Yard;
use App\Models\Time;
use Carbon\Carbon;
use App\Http\Requests\Client\MonthlyRentRequest;

class PickController extends Controller
{
    public function index(Request $request, $yard_id, $user_id = null)
    {
        // --- Xác định ngày chọn và thứ trong tuần ---
        $selected_date = $request->query('date', date('Y-m-d'));
        $dayOfWeek = Carbon::parse($selected_date)->dayOfWeek; // 0 = CN
        $dayOfWeek = $dayOfWeek === 0 ? 6 : $dayOfWeek - 1;   // Chuyển sang 0 = Thứ 2, … 6 = CN

        // --- Lấy thông tin sân và quản lý ---
        $yard = Yard::with(['images', 'user', 'type'])->find($yard_id);
        if (!$yard) {
            return redirect()->back()->with('error', 'Sân không tồn tại');
        }

        $managerName = $yard->user?->fullname ?? 'Admin';

        // --- 1. Lấy tất cả khung giờ sân (dùng cho bảng hiển thị) ---
        $timesForUI = Time::where('yard_id', $yard_id)
            ->where('is_classic', 0)
            ->where('status', 0)
            ->orderBy('start')
            ->get();

        // Thêm thuộc tính range cho dễ dùng
        foreach ($timesForUI as $t) {
            $t->range = substr($t->start,0,5) . ' - ' . substr($t->end,0,5);
        }

        // --- 2. Lấy khung giờ khách có thể đặt (có giá) ---
        $times = Time::where('yard_id', $yard_id)
            ->where('is_classic', 0)
            ->where('status', 0)
            ->orderBy('start')
            ->get()
            ->filter(fn($t) => ($t->getPriceByDate($selected_date) ?? 0) > 0);

        foreach ($times as $t) {
            $t->range = substr($t->start,0,5) . ' - ' . substr($t->end,0,5);
        }

        $adminBookedTimes = [];

        // --- 3. Khung giờ admin xác nhận từ OrderDetail ---
        $orderDetails = OrderDetail::join('orders','order_details.order_id','=','orders.order_id')
            ->where('order_details.yard_id',$yard_id)
            ->where('order_details.date',$selected_date)
            ->where('orders.status',1)
            ->pluck('order_details.time')
            ->toArray();

        $adminBookedTimes = array_merge($adminBookedTimes, $orderDetails);

        // --- 4. Khung giờ thuê theo tháng (MonthRent) ---
        $monthRents = MonthRent::where('yard_id', $yard_id)
            ->whereIn('status',[1,3]) // đã xác nhận hoặc đặt cọc
            ->whereDate('from_date','<=',$selected_date)
            ->whereDate('to_date','>=',$selected_date)
            ->get();

        foreach($monthRents as $rent){
            $weekdays = array_map('trim', explode(',', $rent->weekday)); // "0,2,4" → [0,2,4]
            if(in_array($dayOfWeek, $weekdays)){
                $adminBookedTimes[] = trim($rent->start).' - '.trim($rent->end);
            }
        }

        $adminBookedTimes = array_unique(array_map('trim',$adminBookedTimes));
        sort($adminBookedTimes);

        // --- 5. Khung giờ đã chọn trong session ---
        $sessionBookedTimes = [];
        $orders = session('orders',[]);
        foreach($orders as $order){
            if(($order['yard_id'] ?? null) == $yard_id && ($order['date'] ?? null) == $selected_date){
                $sessionBookedTimes = array_merge($sessionBookedTimes, $order['times']);
            }
        }
        $sessionBookedTimes = array_unique(array_map('trim',$sessionBookedTimes));

        // --- 6. Thông tin sân và người dùng ---
        $yard_name  = $yard->name;
        $type_name  = $yard->type->name ?? '';
        $type_id    = $yard->type->type_id ?? null;
        $yard_image = $yard->images->first();
        $user       = auth()->user();
        $userId     = auth()->id();
        $store      = $yard->user?->store;

        // --- 7. Lấy sản phẩm gợi ý từ cửa hàng ---
        $similarProducts = [];
        if ($store) {
            $similarProducts = $store->products()
                ->with('images','sizes')
                ->where('status',0)
                ->whereHas('images')
                ->inRandomOrder()
                ->limit(4)
                ->get()
                ->transform(function($product){
                    $defaultSize = $product->sizes->sortBy('price')->first();
                    $product->default_size_id = $defaultSize->product_size_id ?? null;
                    $product->default_size_name = $defaultSize->name ?? '';
                    $product->default_price = $defaultSize->price ?? $product->price;
                    return $product;
                });
        }

        return view('client.pick', compact(
            'yard_id','yard_name','type_name','type_id',
            'yard_image','times','timesForUI','adminBookedTimes',
            'sessionBookedTimes','user','userId',
            'selected_date','yard','managerName',
            'store','similarProducts'
        ));
    }

    public function storeProduct(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('dang-nhap')
                ->with('alert', 'Vui lòng đăng nhập để thêm sản phẩm');
        }

        $userId = Auth::id();

        // --- Lấy giá và tồn kho ---
        $productSizeId = $request->input('product_size_id') ? (int)$request->product_size_id : null;

        if ($productSizeId) {
            $size = \App\Models\ProductSize::find($productSizeId);
            $price = $size->price ?? 0;
            $stockQty = $size->quantity ?? 0;
            $sizeName = $size->name ?? '';
        } else {
            $product = \App\Models\Product::find($request->product_id);
            $price = $product->price ?? 0;
            $stockQty = $product->quantity ?? 0;
            $sizeName = '';
        }

        if ((int)$request->quantity > $stockQty) {
            return redirect()->back()->with('error', 'Số lượng vượt quá tồn kho !');
        }

        // Lấy session cũ
        $buys = session('buys', []);

        // Kiểm tra xem sản phẩm + size + store đã tồn tại chưa
        $found = false;
        foreach ($buys as &$item) {
            if (
                $item['product_id'] == $request->product_id &&
                ($item['product_size_id'] ?? null) == $productSizeId &&
                ($item['store_id'] ?? null) == ($request->store_id ?? 0)
            ) {
                $item['quantity'] += (int)$request->quantity;
                if ($item['quantity'] > $stockQty) $item['quantity'] = $stockQty;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $buys[] = [
                'user_id'         => $userId,
                'product_id'      => $request->product_id,
                'product_size_id' => $request->product_size_id ?? null,
                'quantity'        => (int)$request->quantity,
                'name'            => $request->name,
                'price'           => $price,
                'image'           => $request->image,
                'store_id'        => $request->store_id ?? 0,
            ];
        }

        session(['buys' => $buys]);

        return redirect()->back()->with('success', 'Đã thêm sản phẩm vào giỏ hàng !');
    }

    public function store(PickRequest $request)
    {
        $yard = Yard::findOrFail($request->input('yard_id'));
        $selected_times = $request->input('selected_times', []);
        if (empty($selected_times)) {
            return redirect()->back()->withErrors(['selected_times' => 'Vui lòng chọn ít nhất một khung giờ.']);
        }

        $total_price = (int) $request->input('total_price');
        $price_per_slot = json_decode($request->input('price_per_slot', '[]'), true);

        // Kiểm tra price_per_slot đúng số lượng
        if (!is_array($price_per_slot) || count($price_per_slot) !== count($selected_times)) {
            return redirect()->back()->withErrors(['price_per_slot' => 'Dữ liệu giá khung giờ không hợp lệ.']);
        }

        $user = auth()->user();
        $created_at = now()->toDateTimeString();

        $is_classic_per_slot = json_decode($request->input('is_classic_per_slot', '[]'), true);

        $order = [
            'user_id'        => $user->user_id,
            'yard_id'        => $yard->yard_id,
            'yard_name'      => $yard->name,
            'type_id'        => $request->input('type_id'),
            'type_name'      => $request->input('type_name') ?? ($yard->type->name ?? ''),
            'name'           => $user->fullname,
            'phone'          => $user->phonenb,
            'times'          => $selected_times,
            'date'           => $request->input('date'),
            'price'          => $total_price,
            'price_per_slot' => $price_per_slot,
            'is_classic'     => $is_classic_per_slot,
            'notes'          => $request->input('notes'),
            'created_at'     => $created_at,
            'yard_owner_id'  => $yard->user_id,
        ];

        // Lưu vào session để dd test
        $orders = session('orders', []);
        $orders[] = $order;
        session(['orders' => $orders]);

        // -- Chỉ dùng để debug, có thể bỏ khi chạy thực tế --
        //dd($orders);

        return redirect()->route('xac-nhan-dat-san', ['user_id' => $yard->user_id]);
    }

    public function storeMonthlyRent(MonthlyRentRequest $request)
    {
        $user = auth()->user();
        $yard_id = $request->input('yard_id');
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        $start = $request->input('time_from');
        $end   = $request->input('time_to');
        $weekdayStr = implode(',', $request->input('weekdays'));

        MonthRent::create([
            'user_id'   => $user->user_id,
            'yard_id'   => $yard_id,
            'weekday'   => $weekdayStr,
            'start'     => $start,
            'end'       => $end,
            'from_date' => $from_date,
            'to_date'   => $to_date,
            'price'     => 0,
            'status'    => 0,
            'date'      => now(),
        ]);

        return redirect()->back()->with('success', 'Đã gửi yêu cầu thuê sân theo tháng! Chờ admin xác nhận.');
    }
}
