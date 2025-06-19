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

    public function search(Request $request)
    {

    }

}
