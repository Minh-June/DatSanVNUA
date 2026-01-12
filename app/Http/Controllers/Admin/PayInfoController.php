<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\PayInfor\UpdateRequest;

class PayInfoController extends Controller
{
    // Hiển thị trang thông tin thanh toán
    public function index()
    {
        $user = Auth::user(); // Lấy thông tin người đang đăng nhập
        return view('admin.payinfo.index', compact('user'));
    }

    public function update(UpdateRequest $request)
    {
        $user = Auth::user();

        // Upload QR code
        $qrPath = $user->qr_code;
        if ($request->hasFile('qr_code')) {
            if ($qrPath && Storage::disk('public')->exists($qrPath)) {
                Storage::disk('public')->delete($qrPath);
            }
            $qrPath = $request->file('qr_code')->store('qrcodes', 'public');
        }

        $user->update([
            'acc_name'   => $request->acc_name,
            'acc_number' => $request->acc_number,
            'acc_type'   => $request->acc_type,
            'qr_code'    => $qrPath,
        ]);

        return redirect()->route('thong-tin-thanh-toan')->with('success', 'Cập nhật thông tin thanh toán thành công!');
    }
}
