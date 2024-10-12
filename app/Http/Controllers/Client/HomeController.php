<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Yard;

class HomeController extends Controller
{
    public function index()
    {
        // Lấy tất cả sân và nhóm theo 'tên sân' và bao gồm hình ảnh
        $groupedSans = Yard::with('images')->get()->groupBy('tensan');

        // Trả về view và truyền dữ liệu
        return view('client.view', ['groupedSans' => $groupedSans]);
    }

    public function home()
    {
        // Lấy tất cả sân và nhóm theo 'tên sân' và bao gồm hình ảnh
        $groupedSans = Yard::with('images')->get()->groupBy('tensan');
    
        // Trả về view và truyền dữ liệu
        return view('client.home', ['groupedSans' => $groupedSans]);
    }
}
