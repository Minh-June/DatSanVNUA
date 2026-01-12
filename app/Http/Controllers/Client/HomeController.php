<?php

namespace App\Http\Controllers\Client;

use Carbon\Carbon; 
use App\Models\Yard;
use App\Models\Type;
use App\Models\Time;
use App\Models\Image;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $yards = Yard::with('type')->get();
        $groupedYards = $yards->filter(fn($yard) => $yard->type !== null)
            ->groupBy(fn($yard) => $yard->type->name);

        // Lấy admin (role = 0) với cột www
        $admin = User::where('role', 0)->first([
            'user_id', 'fullname', 'phonenb', 'email', 'www'
        ]);

        return view('view', [
            'groupedYards' => $groupedYards,
            'admin' => $admin,
        ]);
    }
    
    public function home()
    {
        if (!Auth::check()) {
            return redirect()->route('dang-nhap')->with('alert', 'Yêu cầu đăng nhập');
        }

        // Lấy danh sách sân và ảnh đầu tiên
        $yards = Yard::with('type', 'images')
            ->where('status', 0)
            ->orderBy('yard_id')
            ->get();

        $yardFirstImages = [];
        foreach ($yards as $yard) {
            $firstImage = $yard->images->first()?->image;
            $yardFirstImages[$yard->yard_id] = $firstImage
                ? asset('storage/' . $firstImage)
                : asset('image/football.jpg');
        }

        // Nhóm sân theo loại
        $groupedYards = $yards->filter(fn($yard) => $yard->type !== null)
            ->groupBy(fn($yard) => $yard->type->name);

        // Lấy danh sách loại sân và admin
        $types = Type::all();
        $admin = User::where('role', 0)->first(['user_id', 'fullname', 'phonenb', 'email', 'www']);

        // Lấy đơn từ session
        $buys = session('buys', []);

        // Cập nhật giá cho từng sản phẩm trong session
        foreach ($buys as &$item) {
            if (!empty($item['product_size_id'])) {
                $size = \App\Models\ProductSize::find($item['product_size_id']);
                if ($size) $item['price'] = $size->price;
            } else {
                $product = \App\Models\Product::find($item['product_id']);
                if ($product) $item['price'] = $product->price;
            }
        }
        unset($item);

        // Tổng số lượng & tổng giá
        $totalItems = array_sum(array_column($buys, 'quantity'));
        $totalPrice = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $buys));

        // Trả về view, không còn orderKey hay countdown
        return view('client.home', compact(
            'groupedYards', 'types', 'admin', 'buys', 'totalItems',
            'totalPrice', 'yardFirstImages'
        ));
    }

    public function search(Request $request)
    {
        $date = $request->date;
        $type_id = $request->type;
        $time_from = $request->time_from;
        $time_to = $request->time_to;

        $today = now()->format('Y-m-d');
        $currentTime = now()->format('H:i');

        // Kiểm tra giờ hợp lệ
        if ($time_from >= $time_to) {
            return redirect()->route('trang-chu')->withErrors(['time_to' => 'Giờ kết thúc phải lớn hơn giờ bắt đầu!']);
        }

        if ($date === $today && $time_to <= $currentTime) {
            return redirect()->route('trang-chu')->withErrors(['time_to' => 'Không thể tìm khung giờ đã trôi qua!']);
        }

        // Lấy danh sách slot đã đặt
        $bookedSlots = DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.order_id')
            ->where('orders.status', 1)
            ->whereDate('order_details.date', $date)
            ->select('order_details.yard_id', 'order_details.time')
            ->get()
            ->map(fn($item) => $item->yard_id . '_' . $item->time)
            ->toArray();

        // Lấy sân theo loại + trạng thái
        $yards = Yard::with(['type', 'images', 'times' => fn($q) => $q->where('status',0)])
            ->where('status',0);

        if ($type_id) $yards->where('type_id', $type_id);

        $yards = $yards->get();

        // Lọc times theo date & khung giờ
        foreach ($yards as $yard) {
            $yard->times = $yard->times->filter(function($time) use ($date, $time_from, $time_to, $yard, $bookedSlots) {
                $timeValue = $time->start . ' - ' . $time->end;
                $isBooked = in_array($yard->yard_id . '_' . $timeValue, $bookedSlots);
                $isInRange = $time->start >= $time_from && $time->end <= $time_to;
                return !$isBooked && $isInRange;
            });

            $yard->first_image_url = $yard->images->first()?->image
                ? asset('storage/' . $yard->images->first()->image)
                : asset('image/football.jpg');
        }

        $filteredYards = $yards->filter(fn($y) => $y->times->isNotEmpty());

        if ($filteredYards->isEmpty()) {
            return redirect()->route('trang-chu')->withErrors(['not_found' => 'Không tìm thấy sân phù hợp!']);
        }

        return view('client.home', [
            'groupedYards' => $filteredYards->groupBy(fn($yard) => $yard->type->name ?? 'Không xác định'),
            'types' => Type::all(),
            'selected_date' => $date,
        ]);
    }
}