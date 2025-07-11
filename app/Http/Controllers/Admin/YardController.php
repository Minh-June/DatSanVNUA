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
            ->with('success', 'Cập nhật trạng thái sân thành công !');
    }

    public function create() {
        $types = Type::orderBy('name', 'asc')->get();
        return view('admin.yards.create', compact('types'));
    }

    public function store(StoreRequest $request)
    {
        Yard::create([
            'type_id' => $request->type_id,
            'name' => $request->name,
        ]);

        return redirect()->route('quan-ly-san')->with('success', 'Thêm sân thành công !');
    }

    public function edit($yard_id) {
        $yard = Yard::findOrFail($yard_id);
        $types = Type::orderBy('name', 'asc')->get();
        return view('admin.yards.update', compact('yard', 'types'));
    }

    public function update(UpdateRequest $request, $yard_id)
    {
        $yard = Yard::findOrFail($yard_id);

        $yard->update([
            'type_id' => $request->input('type_id'),
            'name'    => $request->input('name'),
        ]);

        return redirect()->route('quan-ly-san')->with('success', 'Cập nhật sân thành công !');
    }

    public function delete($yard_id, Request $request) {
        $yard = Yard::findOrFail($yard_id);
        $yard->delete();

        return redirect()->route('quan-ly-san', ['type_id' => $request->type_id])->with('success', 'Đã xóa sân thành công !');
    }
}
