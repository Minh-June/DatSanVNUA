<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SuccessController extends Controller
{
    public function index()
    {
        // Láº¥y danh sĂ¡ch Ä‘Æ¡n hĂ ng tá»« session
        $orders = session('orders', []);
        return view('client.success', compact('orders'));
    }

    public function delete(Request $request)
    {
        // Láº¥y danh sĂ¡ch Ä‘Æ¡n hĂ ng tá»« session
        $orders = session('orders', []);
        
        // Láº¥y index cá»§a Ä‘Æ¡n hĂ ng cáº§n xĂ³a
        $index = $request->input('index');
        
        // Kiá»ƒm tra náº¿u tá»“n táº¡i Ä‘Æ¡n hĂ ng táº¡i index nĂ y
        if (isset($orders[$index])) {
            // XĂ³a Ä‘Æ¡n hĂ ng khá»i session
            unset($orders[$index]);
            
            // Cáº­p nháº­t láº¡i session vá»›i danh sĂ¡ch Ä‘Æ¡n hĂ ng Ä‘Ă£ xĂ³a
            session(['orders' => array_values($orders)]); // array_values() Ä‘á»ƒ reset láº¡i chá»‰ sá»‘ máº£ng
        }

        // Quay láº¡i trang danh sĂ¡ch Ä‘Æ¡n hĂ ng sau khi xĂ³a
        return redirect()->route('xac-nhan-dat-san');
    }
}
