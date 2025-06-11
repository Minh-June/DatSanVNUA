<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Image;
use App\Models\Yard;
use Illuminate\Support\Facades\Storage;
<<<<<<< HEAD
=======
use App\Http\Requests\Admin\ImageYard\UpdateRequest;
>>>>>>> 80d6e7c (Cập nhật giao diện)

class ImageController extends Controller
{
    public function index(Request $request)
    {
        $yards = Yard::orderBy('name', 'asc')->get(); // Lấy danh sách sân

        if ($request->has('yard_id')) {
            $selectedYard = Yard::with('images')->findOrFail($request->yard_id); // Lấy sân và hình ảnh của sân
            return view('admin.imgyards.index', compact('yards', 'selectedYard')); // Truyền dữ liệu sân và hình ảnh
        }

        return view('admin.imgyards.index', compact('yards')); // Trả về khi chưa chọn sân
    }

    public function create()
    {
        $yards = Yard::orderBy('name')->get();  // Lấy danh sách sân
        return view('admin.imgyards.create', compact('yards'));  // Truyền dữ liệu sân
    }

    public function store(Request $request)
    {
        $request->validate([
            'yard_id' => 'required|exists:yards,yard_id',
            'image' => 'required|image|max:2048', // Đảm bảo ảnh đúng định dạng
        ]);
    
        // Lưu ảnh vào thư mục public (storage/app/public)
        $path = $request->file('image')->store('yards', 'public');
    
        // Lưu đường dẫn ảnh vào cơ sở dữ liệu
        Image::create([
            'yard_id' => $request->yard_id,
            'image' => $path,  // Lưu đường dẫn ảnh
        ]);
    
        // Chuyển hướng lại trang với yard_id trong URL
        return redirect()->route('quan-ly-hinh-anh-san', ['yard_id' => $request->yard_id])
                         ->with('success', 'Thêm hình ảnh sân thành công!');
    }

    public function edit($image_id)
    {
        $image = Image::findOrFail($image_id);
        return view('admin.imgyards.update', compact('image'));
    }

<<<<<<< HEAD
    public function update(Request $request, $image_id)
=======
    public function update(UpdateRequest $request, $image_id)
>>>>>>> 80d6e7c (Cập nhật giao diện)
    {
        $request->validate([
            'image' => 'required|image|max:2048',
        ]);

        $image = Image::findOrFail($image_id);

        // Xóa ảnh cũ
        Storage::disk('public')->delete($image->image);

        // Upload ảnh mới
        $path = $request->file('image')->store('yards', 'public');

        $image->update([
            'image' => $path,
        ]);

        return redirect()->route('quan-ly-hinh-anh-san', ['yard_id' => $image->yard_id])
                        ->with('success', 'Cập nhật hình ảnh thành công!');
    }

    public function delete($image_id)
    {
        $image = Image::findOrFail($image_id);
    
        Storage::disk('public')->delete($image->image);
        $image->delete();
    
        // Chuyển hướng lại trang với yard_id trong URL
        return redirect()->route('quan-ly-hinh-anh-san', ['yard_id' => $image->yard_id])
                         ->with('success', 'Xóa hình ảnh thành công!');
    }
}
