<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Store;

class StoresBoardController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->name;

        // Lấy shop status = 0, kèm quan hệ user + products.images + products.sizes
        $query = Store::with(['user', 'products.images', 'products.sizes'])
            ->where('status', 0);

        // Nếu có từ khóa tìm kiếm
        if (!empty($keyword)) {
            $likeKeyword = '%' . $keyword . '%';

            $query->where(function ($q) use ($likeKeyword) {
                $q->where('name', 'like', $likeKeyword)
                ->orWhereHas('products', function ($sub) use ($likeKeyword) {
                    $sub->where('name', 'like', $likeKeyword);
                });
            });
        }

        // 🔹 Random shop và phân trang
        $storesPerPage = 3; // số shop/trang, chỉnh ở đây
        $stores = $query
            ->inRandomOrder()
            ->paginate($storesPerPage);

        // 🔹 Xử lý sản phẩm cho từng shop
        $productsPerShop = 4; // số sản phẩm hiển thị/ngẫu nhiên
        foreach ($stores as $store) {
            if (!empty($keyword)) {
                // Có tìm kiếm → lọc sản phẩm khớp từ khóa, random $productsPerShop
                $store->products = $store->products
                    ->filter(fn ($p) => stripos($p->name, $keyword) !== false)
                    ->shuffle()
                    ->take($productsPerShop)
                    ->values();
            } else {
                // Không tìm kiếm → random $productsPerShop sản phẩm
                $store->products = $store->products
                    ->shuffle()
                    ->take($productsPerShop)
                    ->values();
            }
        }

        return view('client.storesboard', compact('stores'));
    }

    public function store(Request $request)
    {
        // Kiểm tra người dùng đã đăng nhập
        if (!Auth::check()) {
            return redirect()->route('dang-nhap')
                ->with('alert', 'Vui lòng đăng nhập để thêm sản phẩm');
        }

        $userId = Auth::id(); // ID người dùng

        // Lấy giá thực tế và tồn kho
        if (!empty($request->product_size_id)) {
            $size = \App\Models\ProductSize::find($request->product_size_id);
            $price = $size->price ?? 0;
            $stockQty = $size->quantity ?? 0;
        } else {
            $product = \App\Models\Product::find($request->product_id);
            $price = $product->price ?? 0;
            $stockQty = $product->quantity ?? 0;
        }

        // Kiểm tra tồn kho
        if ((int)$request->quantity > $stockQty) {
            return redirect()->back()->with('error', 'Số lượng vượt quá tồn kho !');
        }

        // Lấy session cũ
        $buys = session('buys', []);

        // Kiểm tra sản phẩm đã có trong session (cùng product_id + size + store_id)
        $found = false;
        foreach ($buys as &$item) {
            if ($item['product_id'] == $request->product_id
                && ($item['product_size_id'] ?? null) == ($request->product_size_id ?? null)
                && ($item['store_id'] ?? null) == ($request->store_id ?? null)
            ) {
                $item['quantity'] += (int)$request->quantity;
                if ($item['quantity'] > $stockQty) $item['quantity'] = $stockQty;
                $found = true;
                break;
            }
        }

        // Nếu chưa có → thêm mới
        if (!$found) {
            $buys[] = [
                'user_id'         => $userId,
                'product_id'      => $request->product_id,
                'product_size_id' => $request->product_size_id ?? null,
                'quantity'        => (int)$request->quantity,
                'name'            => $request->name,
                'price'           => $price,
                'image'           => $request->image,
                'store_id'        => $request->store_id ?? 0,
            ];
        }

        // Lưu lại session
        session(['buys' => $buys]);

        return redirect()->back()->with('success', 'Đã thêm sản phẩm vào giỏ hàng !');
    }
}
