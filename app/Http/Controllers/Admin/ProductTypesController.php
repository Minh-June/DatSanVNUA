<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductType;
use App\Models\Store;

class ProductTypesController extends Controller
{
    public function index($store_id)
    {
        $store = Store::findOrFail($store_id);
        $types = ProductType::where('store_id', $store_id)->get();
        return view('admin.producttypes.index', compact('store', 'types'));
    }

    public function create($store_id)
    {
        $store = Store::findOrFail($store_id);
        return view('admin.producttypes.create', compact('store'));
    }

    public function store(Request $request, $store_id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        ProductType::create([
            'store_id' => $store_id,
            'name' => $request->name,
        ]);

        return redirect()->route('quan-ly-loai-san-pham', $store_id)
                         ->with('success', 'Thêm loại sản phẩm thành công !');
    }

    public function edit($type_id)
    {
        $type = ProductType::findOrFail($type_id);
        $store = $type->store; // Lấy store qua quan hệ belongsTo

        return view('admin.producttypes.update', compact('type', 'store'));
    }

    public function update(Request $request, $type_id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $type = ProductType::findOrFail($type_id);
        $type->update(['name' => $request->name]);

        return redirect()->route('quan-ly-loai-san-pham', $type->store_id)
                         ->with('success', 'Cập nhật loại sản phẩm thành công !');
    }

    public function delete($type_id)
    {
        ProductType::findOrFail($type_id)->delete();
        return back()->with('success', 'Xóa loại sản phẩm thành công !');
    }
}
