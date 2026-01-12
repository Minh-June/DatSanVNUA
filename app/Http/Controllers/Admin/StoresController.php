<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\User;

class StoresController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role == 0) { // Admin: xem tất cả cửa hàng
            $stores = Store::with('user')->get();
        } elseif ($user->role == 2) { // Chủ thầu: xem cửa hàng của mình và nhân viên
            $stores = Store::with('user')->whereIn('user_id', function($q) use ($user) {
                $q->select('user_id')->from('users')->where('user_id', $user->user_id)
                ->orWhere('manager_id', $user->user_id);
            })->get();
        } elseif ($user->role == 3) { // Nhân viên: xem cửa hàng của mình và chủ thầu quản lý
            $userIds = [$user->user_id];
            if ($user->manager_id) $userIds[] = $user->manager_id;
            $stores = Store::with('user')->whereIn('user_id', $userIds)->get();
        } else {
            $stores = collect();
        }

        // Lấy danh sách nhân viên của tất cả chủ cửa hàng có trong danh sách trên
        $managerIds = $stores->pluck('user.user_id')->filter()->unique();
        $employees = \App\Models\User::whereIn('manager_id', $managerIds)->get();

        return view('admin.stores.index', compact('stores', 'employees'));
    }

    public function updateStatus(Request $request, $store_id)
    {
        $store = Store::find($store_id);

        if (!$store) {
            return redirect()->back()->with('error', 'Không tìm thấy cửa hàng.');
        }

        $store->status = $request->input('status');
        $store->save();

        return redirect()->back()->with('success', 'Cập nhật trạng thái cửa hàng thành công !');
    }

    // Hiển thị form thêm cửa hàng mới 
    public function create() 
    { 
        return view('admin.stores.create');
    }

    public function store(Request $request) 
    { 
        $request->validate([ 
            'name' => 'required|string|max:255', 
            'status' => 'required|in:0,1',
        ]); 
        
        $user = auth()->user(); // Lấy user hiện tại 
        
        $store = Store::create([ 
            'name' => $request->input('name'), 
            'status' => $request->input('status'), 
            'user_id' => $user->user_id, // Gán user đang đăng nhập 
        ]); 
        
        return redirect()->route('quan-ly-cua-hang') ->with('success', 'Thêm cửa hàng thành công !'); 
    }

    public function edit($store_id)
    {
        $store = Store::findOrFail($store_id);
        return view('admin.stores.update', compact('store'));
    }

    public function update(Request $request, $store_id)
    {
        // Validate đầu vào
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Tìm cửa hàng
        $store = Store::findOrFail($store_id);

        // Cập nhật tên
        $store->name = $request->name;
        $store->save();

        // Quay lại danh sách cửa hàng với thông báo
        return redirect()->route('quan-ly-cua-hang')->with('success', 'Cập nhật thông tin cửa hàng thành công !');
    }

    public function delete($store_id)
    {
        $store = Store::findOrFail($store_id);
        $store->delete();

        return redirect()->route('quan-ly-cua-hang')
                        ->with('success', 'Xóa cửa hàng thành công!');
    }
}
