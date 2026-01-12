<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Type\StoreRequest;
use App\Http\Requests\Admin\Type\UpdateRequest;
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
        return view('admin.types.create');
    }

    public function store(StoreRequest $request)
    {
        Type::create($request->validated());
        return redirect()->route('quan-ly-loai-san')->with('success', 'Thêm loại sân thành công!');
    }

    public function edit($type_id)
    {
        $type = Type::findOrFail($type_id);
        return view('admin.types.update', compact('type'));
    }

    public function update(UpdateRequest $request, $type_id)
    {
        $type = Type::findOrFail($type_id);
        $type->update($request->validated());
        return redirect()->route('quan-ly-loai-san')->with('success', 'Cập nhật loại sân thành công!');
    }

    public function delete($type_id)
    {
        $type = Type::findOrFail($type_id);
        $type->delete();
        return redirect()->route('quan-ly-loai-san')->with('success', 'Xóa loại sân thành công!');
    }
}
