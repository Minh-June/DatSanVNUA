<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Yard;
use App\Models\Type;

class YardController extends Controller
{
    // Hiển thị danh sách sân
    public function index(Request $request) {
        $query = Yard::with('type')->orderBy('name', 'asc');
    
        // Kiểm tra nếu có filter theo thể loại sân
        if ($request->has('type_id') && $request->type_id != '') {
            $query->where('type_id', $request->type_id);
        }
    
        $yards = $query->get();
        $types = Type::all(); // Lấy tất cả thể loại sân để hiển thị trong dropdown
    
        return view('admin.yards.index', compact('yards', 'types'));
    }

    // Hiển thị form thêm sân
    public function create() {
        $types = Type::orderBy('name', 'asc')->get(); // Lấy tất cả các thể loại sân
        return view('admin.yards.create', compact('types'));
    }

    // Lưu sân mới
    public function store(Request $request) {
        $request->validate([
            'type_id' => 'required|exists:types,type_id',
            'name' => 'required|string|max:255',
        ]);
    
        // Kiểm tra tên sân đã tồn tại chưa
        $exists = Yard::where('name', $request->name)->exists();
        if ($exists) {
            return redirect()->back()->with('error', 'Tên sân đã tồn tại, vui lòng nhập lại tên sân khác.');
        }
    
        Yard::create([
            'type_id' => $request->type_id,
            'name' => $request->name,
        ]);
    
        return redirect()->route('quan-ly-san')->with('success', 'Đã thêm sân thành công.');
    }

    // Hiển thị form chỉnh sửa sân
    public function edit($yard_id) {
        $yard = Yard::findOrFail($yard_id); // Tìm sân theo yard_id
        $types = Type::orderBy('name', 'asc')->get(); // Lấy tất cả thể loại sân
        return view('admin.yards.update', compact('yard', 'types'));
    }

    // Cập nhật thông tin sân
    public function update(Request $request, $yard_id) {
        $yard = Yard::findOrFail($yard_id);
    
        $request->validate([
            'type_id' => 'required|exists:types,type_id',
            'name' => 'required|string|max:255',
        ]);
    
        // Kiểm tra tên sân trùng (ngoại trừ chính sân đang cập nhật)
        $exists = Yard::where('name', $request->name)
                    ->where('yard_id', '!=', $yard_id)
                    ->exists();
        if ($exists) {
            return redirect()->back()->with('error', 'Tên sân đã tồn tại, vui lòng nhập lại tên sân khác.');
        }
    
        $yard->update([
            'type_id' => $request->type_id,
            'name' => $request->name,
        ]);
    
        return redirect()->route('quan-ly-san', ['type_id' => $request->type_id])->with('success', 'Đã cập nhật sân thành công.');
    }

    // Xóa sân
    public function delete($yard_id, Request $request) {
        $yard = Yard::findOrFail($yard_id);
        $yard->delete();

        // Trả về trang danh sách sân với 'type_id' còn lại trong URL
        return redirect()->route('quan-ly-san', ['type_id' => $request->type_id])->with('success', 'Đã xóa sân thành công.');
    }
}
