<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $buys = session('buys', []);
        $totalPrice = array_sum(array_map(fn($i)=>$i['price']*$i['quantity'],$buys));

        return view('client.cart', [
            'buys'=>$buys,
            'totalPrice'=>$totalPrice
        ]);
    }

    public function delete(Request $request)
    {
        $index = $request->input('index');
        $buys = session('buys', []);
        if(isset($buys[$index])){
            unset($buys[$index]);
            session(['buys'=>array_values($buys)]);
        }
        return response()->json(['success'=>true]);
    }

    public function updateQuantity(Request $request)
    {
        $index = $request->input('index');
        $quantity = (int) $request->input('quantity');
        $buys = session('buys', []);
        if(isset($buys[$index])){
            $buys[$index]['quantity']=$quantity;
            session(['buys'=>$buys]);
        }
        return response()->json(['success'=>true]);
    }
}
