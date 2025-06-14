<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Yard;
use App\Models\Type;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\Yard\StoreRequest;
use App\Http\Requests\Admin\Yard\UpdateRequest;

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

    public function updateStatus(Request $request)
    {
        $request->validate([
            'yard_id' => 'required|exists:yards,yard_id',
            'status' => 'required|in:0,1',
        ]);

        $yard = Yard::find($request->yard_id);
        $yard->status = $request->status;
        $yard->save();

        return redirect()->route('quan-ly-san', ['type_id' => request('type_id')])
            ->with('success', 'Cập nhật trạng thái sân thành công!');
    }

    // Hiển thị form thêm sân
    public function create() {
        $types = Type::orderBy('name', 'asc')->get();
        return view('admin.yards.create', compact('types'));
    }

    // Lưu sân mới
    public function store(StoreRequest $request) {
        if (Yard::where('name', $request->name)->exists()) {
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
        $yard = Yard::findOrFail($yard_id);
        $types = Type::orderBy('name', 'asc')->get();
        return view('admin.yards.update', compact('yard', 'types'));
    }

    // Cập nhật thông tin sân
    public function update(UpdateRequest $request, $yard_id) {
        $yard = Yard::findOrFail($yard_id);

        // Kiểm tra trùng tên ngoại trừ chính nó
        if (Yard::where('name', $request->name)->where('yard_id', '!=', $yard_id)->exists()) {
            return redirect()->back()->with('error', 'Tên sân đã tồn tại, vui lòng nhập lại tên sân khác.');
        }

        $yard->update([
            'type_id' => $request->type_id,
            'name' => $request->name,
        ]);

        return redirect()->route('quan-ly-san')->with('success', 'Đã cập nhật sân thành công.');
    }

    // XĂ³a sĂ¢n
    public function delete($yard_id, Request $request) {
        $yard = Yard::findOrFail($yard_id);
        $yard->delete();

        return redirect()->route('quan-ly-san', ['type_id' => $request->type_id])->with('success', 'Đã xóa sân thành công.');
    }
}
