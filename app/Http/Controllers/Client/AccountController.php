<?php

namespace App\Http\Controllers\Client;

use App\Http\Requests\Client\PasswordRequest;
use App\Http\Requests\Client\InforRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductOrder;
use Illuminate\Http\Request;
use App\Models\MonthRent;
use App\Models\User;
use Carbon\Carbon;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $selectedDate = $request->input('date') ?: today()->toDateString();

        $orders = $user->orders()
            ->with('orderDetails.yard.type')
            ->whereDate('date', $selectedDate)
            ->orderBy('date', 'desc')
            ->get();

        foreach ($orders as $order) {
            // Chỉ sắp xếp, không groupBy ở đây để tránh mất record
            $order->sortedDetails = $order->orderDetails->sortBy(function ($detail) {
                return $detail->date . ' ' . substr($detail->time, 0, 5);
            });
        }

        // Nhóm theo ngày đặt để tính tổng rowspan cho cột STT và Ngày đặt
        $groupedOrders = $orders->groupBy(fn($order) => $order->date);

        return view('client.account.index', compact('groupedOrders', 'selectedDate'));
    }

    public function fixedOrders(Request $request)
    {
        $user = Auth::user();
        $selectedDate = $request->input('date', today()->toDateString()); // mặc định hôm nay

        // Lấy các đơn có ngày đặt = selectedDate
        $orders = MonthRent::with('yard.type')
            ->where('user_id', $user->user_id)
            ->whereDate('date', $selectedDate)
            ->orderBy('from_date', 'asc')
            ->get();

        foreach ($orders as $order) {
            $order->times = Carbon::parse($order->start)->format('H:i') . ' - ' . Carbon::parse($order->end)->format('H:i');
            $order->totalPrice = $order->price;
        }

        return view('client.account.index-fixed', compact('orders', 'selectedDate'));
    }

    public function indexBuy(Request $request)
    {
        $userId = auth()->id();

        $selectedDate = $request->input('date') ?: now()->format('Y-m-d');

        $orders = ProductOrder::with(['orderDetails.product', 'orderDetails.size'])
            ->where('user_id', $userId)
            ->whereDate('date', $selectedDate)
            ->orderBy('date', 'desc')
            ->get();

        // Gộp các đơn theo Ngày đặt (yyyy-mm-dd)
        $groupedOrders = $orders->groupBy(function ($order) {
            return \Carbon\Carbon::parse($order->date)->format('Y-m-d H:i:s');
        });

        return view('client.account.index-buy', compact('groupedOrders', 'selectedDate'));
    }

    // Hiển thị form cập nhật thông tin cá nhân
    public function editInfor()
    {
        $user = Auth::user();
        return view('client.account.infor', compact('user'));
    }

    // Cập nhật thông tin cá nhân
    public function updateInfor(InforRequest $request)
    {
        $user = Auth::user();

        $user->fullname = $request->fullname;
        $user->gender = $request->gender;
        $user->birthdate = $request->birthdate;
        $user->phonenb = $request->phonenb;
        $user->email = $request->email;

        // Nếu là admin thì cập nhật website
        if ($user->role == 0 && $request->filled('www')) {
            $user->www = $request->www;
        }

        // Nếu có upload ảnh mới
        if ($request->hasFile('image')) {
            $path = $request->file('image')->storeAs(
                'avatars',
                uniqid() . '_' . $request->file('image')->getClientOriginalName(),
                'public'
            );
            $user->image = $path;
        }

        $user->save();

        return redirect()->route('thong-tin-ca-nhan')->with('success', 'Cập nhật thông tin cá nhân thành công !');
    }

    public function editPassword()
    {
        return view('client.account.password');
    }

    public function updatePassword(PasswordRequest $request)
    {
        $user = Auth::user();

        if (!Hash::check($request->matkhau_hientai, $user->password)) {
            return back()->withErrors(['matkhau_hientai' => 'Mật khẩu hiện tại không đúng.'])->withInput();
        }

        $user->password = Hash::make($request->matkhau_moi);
        $user->save();

        return redirect()->route('thay-doi-mat-khau')->with('success', 'Cập nhật mật khẩu thành công !');
    }

    public function deleteAvatar()
    {
        $user = Auth::user();

        if ($user->image && Storage::disk('public')->exists($user->image)) {
            Storage::disk('public')->delete($user->image);
        }

        $user->image = null;
        $user->save();

        return redirect()->route('thong-tin-ca-nhan')->with('success', 'Xóa ảnh đại diện thành công !');
    }

    public function delete(Request $request)
    {
        $user = Auth::user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Mật khẩu không đúng. Không thể xóa tài khoản.');
        }

        $user->delete();
        Auth::logout();

        return redirect()->route('dang-nhap')->with('success', 'Tài khoản đã được xóa thành công !');
    }


}
