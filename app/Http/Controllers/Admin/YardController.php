<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\Yard\StoreRequest;
use App\Http\Requests\Admin\Yard\UpdateRequest;
use App\Models\Yard;

class YardController extends Controller
{
    public function index() {
        // Lấy tất cả các sân từ bảng 'tbl_san', sắp xếp theo 'tensan' và 'sosan'
        $yards = Yard::orderBy('tensan', 'asc')
            ->orderBy('sosan', 'asc')
            ->get();
    
        // Truyền dữ liệu sang view
        return view('admin.yards.index', compact('yards'));
    }
    
    public function create() {
        return view('admin.yards.create');
    }
    
    public function store(StoreRequest $request) {
        // Tạo sân mới
        $yard = new Yard();
        $yard->tensan = $request->input('tensan');
        $yard->sosan = $request->input('sosan');
        $yard->save();
        
        // Chuyển hướng về trang danh sách sân với thông báo thành công
        return redirect()->route('quan-ly-san')->with('success', 'Sân đã được thêm thành công.');
    }
    
    public function edit($san_id) {
        // Logic để lấy thông tin sân dựa trên san_id
        $yard = Yard::findOrFail($san_id);
        return view('admin.yards.update', compact('yard'));
    }

    public function update(UpdateRequest $request, $san_id) {
        // Lấy sân cần cập nhật
        $yard = Yard::findOrFail($san_id);
    
        // Cập nhật thông tin
        $yard->tensan = $request->input('tensan');
        $yard->sosan = $request->input('sosan');
        $yard->save();
    
        // Chuyển hướng về trang danh sách sân với thông báo thành công
        return redirect()->route('quan-ly-san')->with('success', 'Thông tin sân đã được cập nhật thành công.');
    }

    public function delete($san_id) {
        // Logic để xóa sân dựa trên san_id
        $yard = Yard::find($san_id);
        if ($yard) {
            $yard->delete();
            return redirect()->route('quan-ly-san')->with('success', 'Sân đã được xóa thành công.');
        }
        return redirect()->route('quan-ly-san')->with('error', 'Không tìm thấy sân.');
    }
    
}
