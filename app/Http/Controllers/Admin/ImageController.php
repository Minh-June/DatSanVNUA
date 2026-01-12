<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Image;
use App\Models\Yard;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\ImageYard\UpdateRequest;
use App\Http\Requests\Admin\ImageYard\StoreRequest;

class ImageController extends Controller
{
    public function index(Request $request)
    {
        $yards = Yard::orderBy('name', 'asc')->get(); // Lấy danh sách sân
        $selectedYard = null;
        $canManage = false;

        if ($request->has('yard_id')) {
            $selectedYard = Yard::with('images', 'user', 'type')->findOrFail($request->yard_id);

            $user = auth()->user();
            $owner = $selectedYard->user;

            if ($owner) {
                // Quyền quản lý
                if ($user->role == 0 && $owner->role == 0 && $user->user_id == $owner->user_id) {
                    $canManage = true; // admin xem sân của chính họ
                } elseif ($user->role == 2 && $user->user_id == $owner->user_id) {
                    $canManage = true; // chủ sân quản lý sân của mình
                }
                // role=3 luôn false
            }
        }

        return view('admin.imgyards.index', compact('yards', 'selectedYard', 'canManage'));
    }

    public function create(Request $request)
    {
        $yards = Yard::orderBy('name')->get();
        $selectedYard = null;
        
        if ($request->has('yard_id')) {
            $selectedYard = Yard::find($request->yard_id);
        }

        return view('admin.imgyards.create', compact('yards', 'selectedYard'));
    }

    public function store(StoreRequest $request)
    {
        $path = $request->file('image')->store('yards', 'public');

        Image::create([
            'yard_id' => $request->yard_id,
            'image' => $path,
        ]);

        return redirect()->route('quan-ly-hinh-anh-san', ['yard_id' => $request->yard_id])
                        ->with('success', 'Thêm hình ảnh sân thành công !');
    }

    public function edit($image_id)
    {
        $image = Image::findOrFail($image_id);
        return view('admin.imgyards.update', compact('image'));
    }

    public function update(UpdateRequest $request, $image_id)
    {
        $image = Image::findOrFail($image_id);

        // Nếu có file ảnh mới được chọn
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ khỏi storage
            if ($image->image) {
                Storage::disk('public')->delete($image->image);
            }

            // Lưu ảnh mới
            $path = $request->file('image')->store('yards', 'public');

            // Cập nhật đường dẫn ảnh
            $image->update([
                'image' => $path,
            ]);
        }

        return redirect()->route('quan-ly-hinh-anh-san', ['yard_id' => $image->yard_id])
                        ->with('success', 'Cập nhật hình ảnh thành công !');
    }

    public function delete($image_id)
    {
        $image = Image::findOrFail($image_id);
    
        Storage::disk('public')->delete($image->image);
        $image->delete();
    
        // Chuyển hướng lại trang với yard_id trong URL
        return redirect()->route('quan-ly-hinh-anh-san', ['yard_id' => $image->yard_id])
                         ->with('success', 'Xóa hình ảnh thành công !');
    }
}
