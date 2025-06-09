<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Yard;
use App\Models\Image;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // Lấy tất cả sân kèm theo loại sân (type)
        $yards = Yard::with('type')->get();

        // Nhóm các sân theo tên loại sân (type.name)
        $groupedYards = $yards->groupBy(function ($yard) {
            return $yard->type->name;
        });

        return view('view')->with('groupedYards', $groupedYards);
    }

    public function home()
    {
        if (!Auth::check()) {
            return redirect()->route('dang-nhap')->with('alert', 'Yêu cầu đăng nhập');
        }

        // Lấy sân kèm loại và ảnh
        $yards = Yard::with('type', 'images')->orderBy('yard_id')->get();

        // Gán ảnh đầu tiên cho mỗi sân
        foreach ($yards as $yard) {
            $yard->first_image_url = $yard->images->first()?->url ?? asset('image/football.jpg');
        }

        // Nhóm sân theo loại
        $groupedYards = $yards->groupBy(fn($yard) => $yard->type->name);

        // Lấy orders trong session
        $orders = session('orders') ?? [];

        // Lấy danh sách yard_id trong orders
        $yardIds = collect($orders)->pluck('yard_id')->unique();

        // Lấy ảnh đầu tiên từng sân trong orders
        $yardFirstImages = Image::whereIn('yard_id', $yardIds)->get()
            ->groupBy('yard_id')
            ->map(fn($imgs) => $imgs->first()?->url ?? asset('image/football.jpg'));

        return view('client.home', [
            'groupedYards' => $groupedYards,
            'user_id' => Auth::id(),
            'orders' => $orders,
            'yardFirstImages' => $yardFirstImages,
        ]);
    }
    
}
