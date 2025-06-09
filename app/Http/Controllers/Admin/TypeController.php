<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Type;

class TypeController extends Controller
{
    public function index(Request $request)
    {
        // Danh sách đầy đủ cho dropdown
        $allTypes = Type::orderBy('name', 'asc')->get();

        // Tạo query để lọc danh sách
        $query = Type::query();

        // Nếu chọn loại cụ thể, lọc theo type_id
        if ($request->filled('type_id')) {
            $query->where('type_id', $request->type_id);
        }

        // Lấy kết quả lọc
        $types = $query->orderBy('name', 'asc')->get();

        return view('admin.types.index', compact('types', 'allTypes'));
    }

    public function create()
    {
        return view('admin.types.create');  // Đảm bảo bạn đã có view 'create' cho trang tạo mới
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
    
        // Kiểm tra tên loại sân đã tồn tại chưa
        $exists = Type::where('name', $request->name)->exists();
        if ($exists) {
            return redirect()->back()->with('error', 'Loại sân đã tồn tại, vui lòng nhập loại sân mới.');
        }
    
        $type = new Type();
        $type->name = $request->name;
        $type->save();
    
        return redirect()->route('quan-ly-loai-san')->with('success', 'Thêm loại sân thành công!');
    }

    // Hiển thị form sửa loại sân
    public function edit($type_id)
    {
        $type = Type::find($type_id);  // Tìm loại sân theo ID
        if (!$type) {
            return redirect()->route('quan-ly-loai-san')->with('error', 'Loại sân không tồn tại');
        }
        return view('admin.types.update', compact('type')); // Truyền dữ liệu vào view để chỉnh sửa
    }

    // Cập nhật loại sân
    public function update(Request $request, $type_id)
    {
        $type = Type::find($type_id);
        if (!$type) {
            return redirect()->route('quan-ly-loai-san')->with('error', 'Loại sân không tồn tại');
        }
    
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
    
        // Kiểm tra tên loại sân đã tồn tại (ngoại trừ chính nó)
        $exists = Type::where('name', $request->name)
                    ->where('type_id', '!=', $type_id)
                    ->exists();
        if ($exists) {
            return redirect()->back()->with('error', 'Loại sân đã tồn tại, vui lòng nhập loại sân mới.');
        }
    
        $type->name = $request->input('name');
        $type->save();
    
        return redirect()->route('quan-ly-loai-san')->with('success', 'Cập nhật loại sân thành công');
    }

    public function delete($type_id)
    {
        $type = Type::find($type_id);  // Tìm loại sân theo ID
        if (!$type) {
            return redirect()->route('quan-ly-loai-san')->with('error', 'Loại sân không tồn tại');
        }

        $type->delete();  // Xóa loại sân

        return redirect()->route('quan-ly-loai-san')->with('success', 'Xóa loại sân thành công');
    }

}
