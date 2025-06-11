<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('keyword')) {
            $keyword = strtolower($request->keyword);

            $query->where(function ($q) use ($keyword) {
                $q->whereRaw('LOWER(fullname) LIKE ?', ['%' . $keyword . '%'])
                ->orWhereRaw('LOWER(username) LIKE ?', ['%' . $keyword . '%']);

                if (str_contains($keyword, 'admin')) {
                    $q->orWhere('role', 0);
                } elseif (str_contains($keyword, 'khĂ¡ch') || str_contains($keyword, 'user')) {
                    $q->orWhere('role', 1);
                }
            });
        }

        $xem_user = null;
        if ($request->filled('xem')) {
            $xem_user = User::find($request->xem);
        }

        $users = $query->orderBy('user_id', 'desc')->paginate(15);

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
            'role' => 'required|in:0,1',
        ]);

        $user = User::findOrFail($user_id);
        $user->role = (int) $request->role;
        $user->save();

        return redirect()->route('quan-ly-nguoi-dung')->with('success', 'ÄĂ£ cáº­p nháº­t vai trĂ² ngÆ°á»i dĂ¹ng!');
    }

    public function delete($user_id)
    {
        $user = User::find($user_id);
        if (!$user) {
            return redirect()->route('quan-ly-nguoi-dung')->with('error', 'NgÆ°á»i dĂ¹ng khĂ´ng tá»“n táº¡i.');
        }

        // KhĂ´ng cho xĂ³a admin
        if ($user->role == 0) {
            return redirect()->route('quan-ly-nguoi-dung')->with('error', 'KhĂ´ng thá»ƒ xĂ³a tĂ i khoáº£n admin.');
        }

        $user->delete();

        return redirect()->route('quan-ly-nguoi-dung')->with('success', 'XĂ³a ngÆ°á»i dĂ¹ng thĂ nh cĂ´ng.');
    }


}
