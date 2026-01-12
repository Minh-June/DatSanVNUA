<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class StoreDetailController extends Controller
{
    public function index($store_id, Request $request)
    {
        // Lấy cửa hàng và chủ shop
        $store = Store::with('user')->findOrFail($store_id);

        // Lấy query sản phẩm và load ảnh + sizes
        $productQuery = $store->products()->with('images', 'sizes');

        // Tìm kiếm theo tên
        if ($request->filled('search')) {
            $productQuery->where('name', 'like', "%{$request->search}%");
        }

        // Lọc theo loại sản phẩm
        if ($request->filled('type')) {
            $productQuery->where('product_type_id', $request->type);
        }

        // Lấy tất cả sản phẩm (giữ bản ghi size riêng)
        $allProducts = $productQuery->get();

        // Map ảnh theo tên sản phẩm (để tất cả size cùng tên dùng chung ảnh)
        $productImages = [];
        foreach ($allProducts as $product) {
            if (!isset($productImages[$product->name]) && $product->images->count() > 0) {
                $productImages[$product->name] = $product->images->first()->image;
            }
        }

        // Lọc trùng tên + giá (cùng tên, khác giá => vẫn hiện cả 2)
        $uniqueProducts = $allProducts->unique(function($item) {
            return $item->name . '-' . $item->price;
        })->values();

        // 🔀 Random các sản phẩm trước khi sort
        $uniqueProducts = $uniqueProducts->shuffle();

        // 🔹 Sắp xếp theo giá thực tế của sản phẩm (size rẻ nhất)
        if ($request->filled('sort')) {
            if ($request->sort === 'price_asc') {
                $uniqueProducts = $uniqueProducts->sortBy(function($p) {
                    return $p->sizes->min('price') ?? $p->price;
                })->values();
            } elseif ($request->sort === 'price_desc') {
                $uniqueProducts = $uniqueProducts->sortByDesc(function($p) {
                    return $p->sizes->min('price') ?? $p->price;
                })->values();
            }
        }

        // Phân trang thủ công
        $page = $request->get('page', 1);
        $perPage = 8; // số sản phẩm/trang
        $products = new LengthAwarePaginator(
            $uniqueProducts->forPage($page, $perPage),
            $uniqueProducts->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Lấy danh sách loại sản phẩm duy nhất
        $productTypes = $store->products()
            ->with('type')
            ->get()
            ->pluck('type')
            ->unique('product_type_id')
            ->values();

        return view('client.storedetail', compact('store', 'products', 'productTypes', 'productImages'));
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('dang-nhap')
                ->with('alert', 'Vui lòng đăng nhập để thêm sản phẩm');
        }

        $userId = Auth::id();

        // Lấy giá và size thực tế
        $price = 0;
        $product_size_id = null;

        if (!empty($request->product_size_id)) {
            // Nếu có size được gửi từ form
            $size = \App\Models\ProductSize::find($request->product_size_id);
            if ($size) {
                $price = $size->price;
                $product_size_id = $size->product_size_id;
            }
        } else {
            // Nếu không có size → lấy giá sản phẩm
            $product = \App\Models\Product::find($request->product_id);
            if ($product) {
                $price = $product->price;
            }
        }

        // Lấy session cũ
        $buys = session('buys', []);

        // Thêm vào session
        $buys[] = [
            'user_id'         => $userId,
            'product_id'      => $request->product_id,
            'product_size_id' => $product_size_id,
            'quantity'        => (int)$request->quantity,
            'name'            => $request->name,
            'price'           => $price,
            'image'           => $request->image,
            'store_id'        => $request->store_id ?? 0,
        ];

        session(['buys' => $buys]);

        return redirect()->back()->with('success', 'Thêm sản phẩm vào giỏ hàng thành công !');
    }
}
