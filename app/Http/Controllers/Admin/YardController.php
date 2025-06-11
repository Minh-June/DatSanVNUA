<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Yard;
use App\Models\Type;
use Illuminate\Http\Request;
<<<<<<< HEAD
use App\Models\Yard;
use App\Models\Type;
=======
use App\Http\Requests\Admin\Yard\StoreRequest;
use App\Http\Requests\Admin\Yard\UpdateRequest;
>>>>>>> 80d6e7c (Cập nhật giao diện)

class YardController extends Controller
{
    // Hiển thị danh sách sân
    public function index(Request $request) {
        $query = Yard::with('type')->orderBy('name', 'asc');
<<<<<<< HEAD
    
=======

>>>>>>> 80d6e7c (Cập nhật giao diện)
        // Kiểm tra nếu có filter theo thể loại sân
        if ($request->has('type_id') && $request->type_id != '') {
            $query->where('type_id', $request->type_id);
        }
<<<<<<< HEAD
    
        $yards = $query->get();
        $types = Type::all(); // Lấy tất cả thể loại sân để hiển thị trong dropdown
    
=======

        $yards = $query->get();
        $types = Type::all(); // Lấy tất cả thể loại sân để hiển thị trong dropdown

>>>>>>> 80d6e7c (Cập nhật giao diện)
        return view('admin.yards.index', compact('yards', 'types'));
    }

    // Hiển thị form thêm sân
    public function create() {
<<<<<<< HEAD
        $types = Type::orderBy('name', 'asc')->get(); // Lấy tất cả các thể loại sân
=======
        $types = Type::orderBy('name', 'asc')->get();
>>>>>>> 80d6e7c (Cập nhật giao diện)
        return view('admin.yards.create', compact('types'));
    }

    // Lưu sân mới
<<<<<<< HEAD
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
    
=======
    public function store(StoreRequest $request) {
        if (Yard::where('name', $request->name)->exists()) {
            return redirect()->back()->with('error', 'Tên sân đã tồn tại, vui lòng nhập lại tên sân khác.');
        }

>>>>>>> 80d6e7c (Cập nhật giao diện)
        Yard::create([
            'type_id' => $request->type_id,
            'name' => $request->name,
        ]);
<<<<<<< HEAD
    
=======

>>>>>>> 80d6e7c (Cập nhật giao diện)
        return redirect()->route('quan-ly-san')->with('success', 'Đã thêm sân thành công.');
    }

    // Hiển thị form chỉnh sửa sân
    public function edit($yard_id) {
<<<<<<< HEAD
        $yard = Yard::findOrFail($yard_id); // Tìm sân theo yard_id
        $types = Type::orderBy('name', 'asc')->get(); // Lấy tất cả thể loại sân
=======
        $yard = Yard::findOrFail($yard_id);
        $types = Type::orderBy('name', 'asc')->get();
>>>>>>> 80d6e7c (Cập nhật giao diện)
        return view('admin.yards.update', compact('yard', 'types'));
    }

    // Cập nhật thông tin sân
<<<<<<< HEAD
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
    
=======
    public function update(UpdateRequest $request, $yard_id) {
        $yard = Yard::findOrFail($yard_id);

        // Kiểm tra trùng tên ngoại trừ chính nó
        if (Yard::where('name', $request->name)->where('yard_id', '!=', $yard_id)->exists()) {
            return redirect()->back()->with('error', 'Tên sân đã tồn tại, vui lòng nhập lại tên sân khác.');
        }

>>>>>>> 80d6e7c (Cập nhật giao diện)
        $yard->update([
            'type_id' => $request->type_id,
            'name' => $request->name,
        ]);
<<<<<<< HEAD
    
=======

>>>>>>> 80d6e7c (Cập nhật giao diện)
        return redirect()->route('quan-ly-san', ['type_id' => $request->type_id])->with('success', 'Đã cập nhật sân thành công.');
    }

    // Xóa sân
    public function delete($yard_id, Request $request) {
        $yard = Yard::findOrFail($yard_id);
        $yard->delete();

<<<<<<< HEAD
        // Trả về trang danh sách sân với 'type_id' còn lại trong URL
=======
>>>>>>> 80d6e7c (Cập nhật giao diện)
        return redirect()->route('quan-ly-san', ['type_id' => $request->type_id])->with('success', 'Đã xóa sân thành công.');
    }
}
