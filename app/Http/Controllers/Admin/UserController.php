<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('keyword')) {
            $keyword = strtolower($request->keyword);

            $query->where(function ($q) use ($keyword) {
                $q->whereRaw('LOWER(fullname) LIKE ?', ['%' . $keyword . '%'])
                ->orWhereRaw('LOWER(username) LIKE ?', ['%' . $keyword . '%'])
                ->orWhere('phonenb', 'like', '%' . $keyword . '%');

                // Tìm theo tên vai trò
                if (str_contains($keyword, 'admin')) {
                    $q->orWhere('role', 0);
                } elseif (str_contains($keyword, 'kha') || str_contains($keyword, 'user')) {
                    $q->orWhere('role', 1);
                } elseif (str_contains($keyword, 'can') || str_contains($keyword, 'can bo')) {
                    $q->orWhere('role', 2);
                }
            });
        }

        $xem_user = null;
        if ($request->filled('xem')) {
            $xem_user = User::find($request->xem);
        }

        $users = $query->orderBy('role', 'desc')->paginate(15);

        return view('admin.users.index', compact('users', 'xem_user'));
    }

    public function show($user_id)
    {
        $user = User::findOrFail($user_id);
        return view('admin.users.show', compact('user'));
    }

    public function updateRole(Request $request, $user_id)
    {
        $request->validate([
            'role' => 'required|in:0,1,2',
        ]);

        $user = User::findOrFail($user_id);
        $user->role = (int) $request->role;
        $user->save();

        return redirect()->route('quan-ly-nguoi-dung')->with('success', 'Đã cập nhật vai trò người dùng !');
    }

    public function delete($user_id)
    {
        $user = User::find($user_id);
        if (!$user) {
            return redirect()->route('quan-ly-nguoi-dung')->with('error', 'Người dùng không tồn tại.');
        }

        if ($user->role == 0) {
            return redirect()->route('quan-ly-nguoi-dung')->with('error', 'Không thể xóa tài khoản admin.');
        }

        $user->delete();

        return redirect()->route('quan-ly-nguoi-dung')->with('success', 'Xóa người dùng thành công.');
    }

    public function reset($user_id)
    {
        $user = User::find($user_id);

        if (!$user) {
            return redirect()->route('quan-ly-nguoi-dung')->with('error', 'Người dùng không tồn tại.');
        }

        $user->password = Hash::make('123456');
        $user->save();

        return redirect()->route('quan-ly-nguoi-dung')->with('success', 'Đã đặt lại mật khẩu về 123456 thành công !');
    }
}
