<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\InforRequest;
use App\Http\Requests\Client\PasswordRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $ordersQuery = $user->orders()->with('orderDetails.yard');

        if ($request->has('date') && $request->date != '') {
            $date = $request->date;
            $ordersQuery->whereDate('date', $date);
        }

        $orders = $ordersQuery->get();

        // NhĂ³m chi tiáº¿t orderDetails theo yard_id vĂ  date
        foreach ($orders as $order) {
            $order->groupedDetails = $order->orderDetails->groupBy(function ($detail) {
                return $detail->yard_id . '_' . $detail->date;
            });
        }

        return view('client.account.index', compact('orders'));
    }

    // Hiá»ƒn thá»‹ form cáº­p nháº­t thĂ´ng tin cĂ¡ nhĂ¢n
    public function editInfor()
    {
        $user = Auth::user(); // Láº¥y thĂ´ng tin ngÆ°á»i dĂ¹ng hiá»‡n táº¡i
        return view('client.account.infor', compact('user'));
    }

    // Cáº­p nháº­t thĂ´ng tin cĂ¡ nhĂ¢n
    public function updateInfor(InforRequest  $request)
    {
        $user = Auth::user(); // Láº¥y thĂ´ng tin ngÆ°á»i dĂ¹ng hiá»‡n táº¡i

        $user->fullname = $request->fullname;
        $user->gender = $request->gender;
        $user->birthdate = $request->birthdate;
        $user->phonenb = $request->phonenb;
        $user->email = $request->email;

        $user->save();

        return redirect()->route('thong-tin-ca-nhan')->with('success', 'Cáº­p nháº­t thĂ´ng tin cĂ¡ nhĂ¢n thĂ nh cĂ´ng.');
    }

    // Hiá»ƒn thá»‹ form thay Ä‘á»•i máº­t kháº©u
    public function editPassword()
    {
        return view('client.account.password');
    }

    // Cáº­p nháº­t máº­t kháº©u
    public function updatePassword(PasswordRequest $request)
    {
        $user = Auth::user();

        // Kiá»ƒm tra máº­t kháº©u hiá»‡n táº¡i
        if (!Hash::check($request->matkhau_hientai, $user->password)) {
            return back()->with('error', 'Máº­t kháº©u hiá»‡n táº¡i khĂ´ng Ä‘Ăºng.');
        }

        // Cáº­p nháº­t máº­t kháº©u má»›i
        $user->password = Hash::make($request->matkhau_moi);
        $user->save();

        return redirect()->route('thay-doi-mat-khau')->with('success', 'Cáº­p nháº­t máº­t kháº©u thĂ nh cĂ´ng.');
    }

    public function delete(Request $request)
    {
        $user = Auth::user();

        // Kiá»ƒm tra máº­t kháº©u
        if (!Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Máº­t kháº©u khĂ´ng Ä‘Ăºng. KhĂ´ng thá»ƒ xĂ³a tĂ i khoáº£n.');
        }

        $user->delete();
        Auth::logout();

        return redirect()->route('dang-nhap')->with('success', 'TĂ i khoáº£n Ä‘Ă£ Ä‘Æ°á»£c xĂ³a thĂ nh cĂ´ng.');
    }

}
