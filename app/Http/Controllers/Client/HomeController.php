<?php

namespace App\Http\Controllers\Client;

use Carbon\Carbon; 
use App\Models\Yard;
use App\Models\Type;
use App\Models\Time;
use App\Models\Image;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\SearchRequest;

class HomeController extends Controller
{
    public function index()
    {
        // Lấy tất cả sân kèm theo loại sân (type)
        $yards = Yard::with('type')->get();

        // Nhóm các sân theo tên loại sân (type.name)
        $groupedYards = $yards->filter(fn($yard) => $yard->type !== null)
            ->groupBy(fn($yard) => $yard->type->name);

        return view('view')->with('groupedYards', $groupedYards);
    }

    public function home()
    {
        if (!Auth::check()) {
            return redirect()->route('dang-nhap')->with('alert', 'Yêu cầu đăng nhập');
        }

        // Lấy tất cả sân đang hiển thị (status = 0), kèm loại và ảnh
        $yards = Yard::with('type', 'images')
            ->where('status', 0) // chỉ lấy sân đang hiện
            ->orderBy('yard_id')
            ->get();

        // Gán ảnh đầu tiên cho mỗi sân
        foreach ($yards as $yard) {
            $yard->first_image_url = $yard->images->first()?->url ?? asset('image/football.jpg');
        }

        // Nhóm theo loại sân
        $groupedYards = $yards->filter(fn($yard) => $yard->type !== null)
            ->groupBy(fn($yard) => $yard->type->name);

        // Lấy đơn trong session
        $orders = session('orders') ?? [];

        // Lấy loại sân để dùng cho modal tìm kiếm
        $types = Type::all();

        return view('client.home', [
            'groupedYards' => $groupedYards,
            'orders' => $orders,
            'user_id' => Auth::id(),
            'types' => $types,
        ]);
    }

    public function search(SearchRequest $request)
    {
        $date = $request->date;
        $type_id = $request->type;
        $time_from = $request->time_from;
        $time_to = $request->time_to;

        $today = now()->format('Y-m-d');
        $currentTime = now()->format('H:i');

        // Kiểm tra nếu chọn khung giờ trong quá khứ
        if ($date === $today && $time_to <= $currentTime) {
            return redirect()->route('trang-chu')->withErrors(['time_to' => 'Không thể tìm khung giờ đã trôi qua !']);
        }

        $bookedSlots = DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.order_id')
            ->where('orders.status', 1)
            ->whereDate('order_details.date', $date)
            ->select('order_details.yard_id', 'order_details.time')
            ->get()
            ->map(fn($item) => $item->yard_id . '_' . $item->time)
            ->toArray();

        $query = Yard::with([
            'type',
            'images',
            'times' => fn($q) => $q->whereDate('date', $date)
                                    ->whereBetween('time', [$time_from, $time_to])
        ])->where('status', 0);

        if ($type_id) {
            $query->where('type_id', $type_id);
        }

        $yards = $query->get();

        foreach ($yards as $yard) {
            $yard->first_image_url = $yard->images->first()?->url ?? asset('image/football.jpg');
            $yard->times = $yard->times->filter(fn($time) => !in_array($yard->yard_id . '_' . $time->time, $bookedSlots));
        }

        $filteredYards = $yards->filter(fn($yard) => $yard->times->isNotEmpty());

        if ($filteredYards->isEmpty()) {
            return redirect()->route('trang-chu')->withErrors(['not_found' => 'Không tìm thấy sân phù hợp !']);
        }

        return view('client.home', [
            'groupedYards' => $filteredYards->groupBy(fn($yard) => $yard->type->name ?? 'Không xác định'),
            'orders' => session('orders') ?? [],
            'user_id' => auth()->id(),
            'types' => Type::all(),
            'selected_date' => $date
        ]);
    }
}