<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductSize;

class ProductSizesController extends Controller
{
    public function index($product_id)
    {
        $product = Product::with('sizes')->findOrFail($product_id);
        $sizes = $product->sizes;

        return view('admin.sizes.index', [
            'sizes' => $sizes,
            'product_id' => $product_id
        ]);
    }

    public function create($product_id)
    {
        return view('admin.sizes.create', compact('product_id'));
    }

    public function store(Request $request, $product_id)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'price' => 'required',
            'quantity' => 'required',
        ]);

        // Loại bỏ dấu '.' hoặc ',' nếu có
        $price = str_replace(['.', ','], '', $request->price);
        $quantity = str_replace(['.', ','], '', $request->quantity);

        ProductSize::create([
            'name' => $request->name,
            'product_id' => $product_id,
            'price' => $price,
            'quantity' => $quantity,
        ]);

        return redirect()->route('quan-ly-size', $product_id)
                        ->with('success', 'Thêm size mới thành công !');
    }

    public function edit($product_id, $id)
    {
        $size = ProductSize::findOrFail($id);
        return view('admin.sizes.update', compact('size', 'product_id'));
    }

    public function update(Request $request, $product_id, $id)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'price' => 'required',
            'quantity' => 'required',
        ]);

        $size = ProductSize::findOrFail($id);

        $price = str_replace(['.', ','], '', $request->price);
        $quantity = str_replace(['.', ','], '', $request->quantity);

        $size->update([
            'name' => $request->name,
            'price' => $price,
            'quantity' => $quantity,
        ]);

        return redirect()->route('quan-ly-size', $product_id)
                        ->with('success', 'Cập nhật size thành công !');
    }

    // Xóa size
    public function delete($product_id, $id)
    {
        $size = ProductSize::findOrFail($id);
        $size->delete();

        return redirect()->route('quan-ly-size', $product_id)
                        ->with('success', 'Xóa size thành công !');
    }
}
