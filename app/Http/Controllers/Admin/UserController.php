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

        if (auth()->check()) {
            $user = auth()->user();

            // Nếu là chủ thầu (role = 2)
            if ($user->role == 2) {
                $query->where(function ($q) use ($user) {
                    $q->where('role', 1)
                    ->orWhere(function($sub) use ($user) {
                        $sub->where('role', 3)
                            ->where('manager_id', $user->user_id);
                    });
                });
            }

            // Nếu là admin (role = 0): xem được tất cả
            elseif ($user->role == 0) {
                // Không cần lọc
            }

            // Các vai trò khác không được vào
            else {
                abort(403, 'Bạn không có quyền xem danh sách người dùng');
            }
        }

        // Tìm kiếm theo từ khóa
        if ($request->filled('keyword')) {
            $keyword = strtolower($request->keyword);

            $query->where(function ($q) use ($keyword) {
                $q->whereRaw('LOWER(fullname) LIKE ?', ['%' . $keyword . '%'])
                ->orWhereRaw('LOWER(username) LIKE ?', ['%' . $keyword . '%'])
                ->orWhere('phonenb', 'like', '%' . $keyword . '%');

                if (str_contains($keyword, 'admin') || str_contains($keyword, 'quản trị')) {
                    $q->orWhere('role', 0);
                } elseif (str_contains($keyword, 'khách') || str_contains($keyword, 'user')) {
                    $q->orWhere('role', 1);
                } elseif (str_contains($keyword, 'chủ') || str_contains($keyword, 'thầu')) {
                    $q->orWhere('role', 2);
                } elseif (str_contains($keyword, 'nhân') || str_contains($keyword, 'viên')) {
                    $q->orWhere('role', 3);
                }
            });
        }

        $xem_user = null;
        if ($request->filled('xem')) {
            $xem_user = User::find($request->xem);
        }

        // Sắp xếp theo thứ tự mong muốn: Khách → Chủ thầu → Nhân viên → Admin
        $users = $query->orderByRaw("
            CASE role
                WHEN 1 THEN 1
                WHEN 2 THEN 2
                WHEN 3 THEN 3
                WHEN 0 THEN 4
                ELSE 5
            END
        ")->paginate(15);

        return view('admin.users.index', compact('users', 'xem_user'));
    }
    
    public function updateRole(Request $request, $user_id)
    {
        $currentUser = auth()->user();
        
        // Chỉ admin (0) và chủ thầu (2) được phép
        if (!in_array($currentUser->role, [0, 2])) {
            abort(403, 'Bạn không có quyền cập nhật vai trò người dùng.');
        }
        
        $request->validate([
            'role' => 'required|in:0,1,2,3', // Cho phép tất cả role
        ]);
        
        $user = User::findOrFail($user_id);
        
        // Nếu người cập nhật là chủ thầu (2), chỉ được cập nhật role = 1 hoặc 3 (khách hàng hoặc nhân viên)
        if ($currentUser->role == 2 && !in_array($request->role, [1, 3])) {
            abort(403, 'Chủ sân chỉ được cập nhật vai trò khách hàng hoặc nhân viên.');
        }

        $user->role = (int) $request->role;

        // Nếu role mới là nhân viên, gán manager_id = user_id người cập nhật
        if ($user->role == 3) {
            $user->manager_id = $currentUser->user_id;
        } else {
            // Các role khác giữ nguyên manager_id hoặc xóa nếu muốn
            $user->manager_id = null; // hoặc giữ nguyên tùy yêu cầu
        }

        $user->save();

        return redirect()->route('quan-ly-nguoi-dung')->with('success', 'Đã cập nhật vai trò người dùng!');
    }

    public function show($user_id)
    {
        $user = User::findOrFail($user_id);
        return view('admin.users.show', compact('user'));
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
