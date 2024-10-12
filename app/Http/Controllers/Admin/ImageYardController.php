<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\ImageYard\StoreRequest;
use App\Http\Requests\Admin\ImageYard\UpdateRequest;
use App\Models\ImageYard;
use App\Models\Yard;

class ImageYardController extends Controller
{

    public function index()
    {
        // Lấy danh sách sân cùng với hình ảnh từ bảng 'tbl_san'
        $sans = Yard::with('images')->orderBy('tensan', 'asc')
            ->orderBy('sosan', 'asc')
            ->get();
        
        // Truyền dữ liệu sang view
        return view('admin.imgyards.index', compact('sans'));
    }    

    public function create()
    {
        // Lấy danh sách sân thể thao chưa có hình ảnh
        $available_san_ids = Yard::whereDoesntHave('images')->get(); // Lấy sân không có hình ảnh
    
        return view('admin.imgyards.create', compact('available_san_ids'));
    }
    
    public function store(StoreRequest $request)
    {
        // Lưu hình ảnh vào thư mục public/yards và lấy đường dẫn
        $path = $request->file('image')->storeAs('yards', $request->file('image')->getClientOriginalName(), 'public');
        
        // Tạo mới bản ghi trong bảng tbl_image
        ImageYard::create([
            'san_id' => $request->san_id,
            'image' => 'yards/' . $request->file('image')->getClientOriginalName(), // Lưu đường dẫn hình ảnh
        ]);
        
        return redirect()->route('quan-ly-hinh-anh-san')->with('success', 'Thêm hình ảnh thành công!');
    }
    
    public function destroy($image_id)
    {
        // Tìm hình ảnh theo image_id
        $image = ImageYard::findOrFail($image_id);

        // Xóa hình ảnh từ hệ thống tệp
        \Storage::disk('public')->delete($image->image);

        // Xóa bản ghi hình ảnh từ cơ sở dữ liệu
        $image->delete();

        return redirect()->route('quan-ly-hinh-anh-san')->with('success', 'Xóa hình ảnh thành công!');
    }

    public function edit($image_id)
    {
        // Lấy hình ảnh từ cơ sở dữ liệu
        $image = ImageYard::find($image_id); // hoặc phương thức lấy hình ảnh phù hợp
        $san = Yard::find($image->san_id); // Giả sử bạn lấy thông tin sân liên quan

        // Truyền biến vào view
        return view('admin.imgyards.update', [
            'image' => $image,
            'tensan' => $san->tensan, // Tên sân
            'sosan' => $san->sosan    // Số sân
        ]);
    }

    public function update(UpdateRequest $request, $image_id)
    {
        // Tìm hình ảnh theo ID
        $image = ImageYard::findOrFail($image_id);
    
        // Xóa hình ảnh cũ trước khi lưu hình ảnh mới
        \Storage::disk('public')->delete($image->image);
    
        // Lưu ảnh mới
        $path = $request->file('image')->storeAs('yards', $request->file('image')->getClientOriginalName(), 'public');
    
        // Cập nhật đường dẫn ảnh trong cơ sở dữ liệu
        $image->image = 'yards/' . $request->file('image')->getClientOriginalName(); // Cập nhật đường dẫn
        $image->save();
    
        // Chuyển hướng về trang danh sách hình ảnh với thông báo thành công
        return redirect()->route('quan-ly-hinh-anh-san')->with('success', 'Cập nhật hình ảnh thành công!');
    }
    
}
