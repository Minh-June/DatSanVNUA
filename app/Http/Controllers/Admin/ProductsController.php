<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Store;
use App\Models\ProductType;
use App\Models\ProductImage;
use App\Models\ProductSize;
use App\Http\Requests\Admin\Products\StoreRequest;
use App\Http\Requests\Admin\Products\UpdateRequest;

class ProductsController extends Controller
{
    public function index($store_id, Request $request)
    {
        $store = Store::findOrFail($store_id);

        $query = Product::where('store_id', $store_id)
                        ->with(['type', 'images', 'sizes'])
                        ->orderBy('product_type_id') // 🔹 nhóm theo loại sản phẩm
                        ->orderBy('name');           // 🔹 sắp xếp trong cùng loại

        // 🔹 Tìm kiếm
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhereHas('type', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                });
            });
        }

        // 🔹 Lấy danh sách sản phẩm
        $products = $query->get();

        return view('admin.products.index', compact('store', 'products'));
    }

    public function create($store_id)
    {
        $store = Store::findOrFail($store_id);
        $types = ProductType::where('store_id', $store_id)->get();
        $productIds = Product::where('store_id', $store_id)->pluck('product_id');
        $sizes = ProductSize::whereIn('product_id', $productIds)->get();

        return view('admin.products.create', compact('store', 'types', 'sizes'));
    }

    public function store(StoreRequest $request, $store_id)
    {
        $product = Product::create([
            'store_id' => $store_id,
            'product_type_id' => $request->product_type_id,
            'product_size_id' => $request->product_size_id,
            'name' => $request->name,
            'description' => implode("\n", $request->description),
            'status' => 0,
        ]);

        // Lưu ảnh
        foreach ($request->file('image') as $file) {
            $path = $file->store('products', 'public');
            ProductImage::create([
                'product_id' => $product->product_id,
                'image' => $path,
            ]);
        }

        // Cập nhật status theo tồn kho
        $this->updateStatusByQuantity($product);

        return response()->json([
            'success' => true,
            'message' => 'Thêm sản phẩm thành công!',
            'redirect' => route('quan-ly-san-pham', $store_id)
        ]);
    }

    public function edit($product_id)
    {
        $product = Product::with(['images', 'sizes'])->findOrFail($product_id);
        $store = Store::findOrFail($product->store_id);
        $types = ProductType::where('store_id', $store->store_id)->get();

        // 🔹 CHỈ lấy size của sản phẩm này
        $productSizes = ProductSize::where('product_id', $product->product_id)
            ->get(['product_size_id', 'name', 'price', 'quantity']);

        $hasSize = $productSizes->count() > 0;

        return view('admin.products.update', compact(
            'product',
            'store',
            'types',
            'productSizes',
            'hasSize'
        ));
    }

    public function update(UpdateRequest $request, $product_id)
    {
        $product = Product::with(['images', 'sizes'])->findOrFail($product_id);

        // 1️⃣ Cập nhật thông tin cơ bản
        $product->update([
            'name' => $request->name,
            'product_type_id' => $request->product_type_id,
            'description' => implode("\n", $request->description),
        ]);

        // 2️⃣ Xử lý ảnh
        if ($request->filled('deleted_images')) {
            $imagesToNull = ProductImage::where('product_id', $product_id)
                ->whereIn('product_image_id', $request->deleted_images)
                ->get();
            foreach ($imagesToNull as $img) {
                if ($img->image && file_exists(storage_path('app/public/' . $img->image))) {
                    unlink(storage_path('app/public/' . $img->image));
                }
                $img->update(['image' => null]);
            }
        }

        if ($request->filled('deleted_records')) {
            $imagesToDelete = ProductImage::where('product_id', $product_id)
                ->whereIn('product_image_id', $request->deleted_records)
                ->get();
            foreach ($imagesToDelete as $img) {
                if ($img->image && file_exists(storage_path('app/public/' . $img->image))) {
                    unlink(storage_path('app/public/' . $img->image));
                }
                $img->delete();
            }
        }

        if ($request->hasFile('image')) {
            $existingImages = $product->images()->get();
            foreach ($request->file('image') as $index => $file) {
                $path = $file->store('products', 'public');
                if (isset($existingImages[$index])) {
                    $oldImage = $existingImages[$index];
                    if ($oldImage->image && file_exists(storage_path('app/public/' . $oldImage->image))) {
                        unlink(storage_path('app/public/' . $oldImage->image));
                    }
                    $oldImage->update(['image' => $path]);
                } else {
                    ProductImage::create([
                        'product_id' => $product_id,
                        'image' => $path,
                    ]);
                }
            }
        }

        // 3️⃣ Xử lý size
        if (empty($request->product_size_id)) {
            ProductSize::where('product_id', $product->product_id)->delete();
            $price = $request->price !== null && $request->price !== '' ? (int) str_replace('.', '', $request->price) : null;
            $quantity = $request->quantity !== null && $request->quantity !== '' ? (int) $request->quantity : null;

            $product->update([
                'product_size_id' => null,
                'price' => $price,
                'quantity' => $quantity,
            ]);
        } else {
            ProductSize::where('product_id', $product->product_id)->delete();
            $product->update([
                'product_size_id' => 1,
                'price' => null,
                'quantity' => null,
            ]);

            $submittedSizes = $request->input('sizes', []);
            foreach ($submittedSizes as $data) {
                if (empty($data['name'])) continue;
                $sizeModel = ProductSize::firstOrCreate([
                    'product_id' => $product->product_id,
                    'name' => $data['name'],
                ]);

                $price = $data['price'] === '' ? null : (int) str_replace('.', '', $data['price']);
                $quantity = $data['quantity'] === '' ? null : (int) $data['quantity'];

                $sizeModel->update([
                    'price' => $price,
                    'quantity' => $quantity,
                ]);
            }
        }

        // Cập nhật status theo tồn kho
        $this->updateStatusByQuantity($product);

        return redirect()->route('quan-ly-san-pham', $product->store_id)
                         ->with('success', 'Cập nhật sản phẩm thành công!');
    }

    public function deleteImage($image_id)
    {
        $image = ProductImage::findOrFail($image_id);
        if (file_exists(storage_path('app/public/' . $image->image))) {
            unlink(storage_path('app/public/' . $image->image));
        }
        $image->delete();
        return back()->with('success', 'Xóa ảnh sản phẩm thành công!');
    }

    public function delete($product_id)
    {
        $product = Product::findOrFail($product_id);
        $product->delete();
        return back()->with('success', 'Xóa sản phẩm thành công!');
    }

    // Bật/Tắt trạng thái thủ công
    public function updateStatus($product_id)
    {
        $product = Product::findOrFail($product_id);
        $product->status = !$product->status;
        $product->save();
        return back()->with('success', 'Cập nhật trạng thái sản phẩm thành công!');
    }

    // 🔹 Hàm phụ: Cập nhật trạng thái tự động theo tồn kho
    protected function updateStatusByQuantity(Product $product)
    {
        $quantity = $product->product_size_id ? $product->sizes->sum('quantity') : $product->quantity;
        $status = ($quantity <= 0) ? 1 : 0; // 1 = Đã ẩn, 0 = Hiện
        if ($product->status != $status) {
            $product->status = $status;
            $product->save();
        }
    }
}
