<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductDetailController extends Controller
{
    public function index($id)
    {
        $product = Product::with(['images', 'sizes', 'store'])->findOrFail($id);
        $store = $product->store;

        $allProducts = Product::where('store_id', $store->store_id)
            ->where('product_id', '!=', $product->product_id) // loại sản phẩm chính
            ->with(['images', 'sizes', 'store'])
            ->get();

        // Lọc trùng tên sản phẩm
        $similarProducts = $allProducts
            ->filter(fn($item) => $item->name != $product->name) // loại sản phẩm chính nếu trùng tên
            ->shuffle()
            ->take(4)
            ->values();

        // Lấy ảnh đại diện cho mỗi sản phẩm
        $productImages = [];
        foreach ($similarProducts as $item) {
            if ($item->images->count() > 0) {
                $productImages[$item->name] = $item->images->first()->image;
            }
        }

        return view('client.productdetail', compact('product', 'store', 'similarProducts', 'productImages'));
    }

    public function store(Request $request)
    {
        $buys = session('buys', []);
        $userId = auth()->id() ?? null;

        // Lấy giá và số lượng thực tế
        $price = 0;
        $stockQty = 0;

        if (!empty($request->product_size_id)) {
            // Sản phẩm có size → lấy từ bảng product_sizes
            $size = \App\Models\ProductSize::find($request->product_size_id);
            if ($size) {
                $price = $size->price;
                $stockQty = $size->quantity;
            }
        } else {
            // Sản phẩm không có size → lấy từ bảng products
            $product = \App\Models\Product::find($request->product_id);
            if ($product) {
                $price = $product->price;
                $stockQty = $product->quantity;
            }
        }

        // Kiểm tra tồn kho
        if ((int)$request->quantity > $stockQty) {
            return redirect()->back()->with('error', 'Số lượng vượt quá tồn kho !');
        }

        // Kiểm tra sản phẩm đã có trong session (cùng product_id, size và store_id)
        $found = false;
        foreach ($buys as &$item) {
            if ($item['product_id'] == $request->product_id
                && ($item['product_size_id'] ?? '') == ($request->product_size_id ?? '')
                && ($item['store_id'] ?? '') == ($request->store_id ?? '')
            ) {
                $item['quantity'] += (int)$request->quantity;

                // Nếu vượt tồn kho size/product, gán bằng tồn kho
                if ($item['quantity'] > $stockQty) $item['quantity'] = $stockQty;

                $found = true;
                break;
            }
        }

        // Nếu chưa có trong session → thêm mới
        if (!$found) {
            $buys[] = [
                'user_id' => $userId,
                'product_id' => $request->product_id,
                'product_size_id' => $request->product_size_id ?? null,
                'quantity' => (int)$request->quantity,
                'name' => $request->name,
                'price' => $price,
                'image' => $request->image,
                'store_id' => $request->store_id ?? 0,
            ];
        }

        // Lưu lại session
        session(['buys' => $buys]);

        return redirect()->back()->with('success', 'Đã thêm sản phẩm vào giỏ hàng !');
    }
}
