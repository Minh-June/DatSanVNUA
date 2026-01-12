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
    public function index(Request $request)
    {
        $user = auth()->user();
        $type_id = $request->query('type_id');

        // Nếu là admin: xem tất cả sân
        if ($user->role == 0) {
            $yards = Yard::with('type', 'user')
                ->when($type_id, fn($q) => $q->where('type_id', $type_id))
                ->get();
        }
        // Nếu là chủ thầu: xem sân của mình
        elseif ($user->role == 2) {
            $yards = Yard::with('type', 'user')
                ->where('user_id', $user->user_id)
                ->when($type_id, fn($q) => $q->where('type_id', $type_id))
                ->get();
        }
        // Nếu là nhân viên: xem sân của chủ thầu mình làm việc cho
        elseif ($user->role == 3) {
            $yards = Yard::with('type', 'user')
                ->where('user_id', $user->manager_id)
                ->when($type_id, fn($q) => $q->where('type_id', $type_id))
                ->get();
        }
        // Các role khác: không có quyền
        else {
            $yards = collect();
        }

        $types = \App\Models\Type::all();

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

    public function create()
    {
        $types = Type::orderBy('name', 'asc')->get();
        return view('admin.yards.create', compact('types'));
    }

    public function store(StoreRequest $request)
    {
        // Tạo sân mới
        Yard::create([
            'type_id' => $request->type_id,
            'name' => $request->name,
            'status' => 0, // Mặc định mới tạo thì hiện
        ]);

        return redirect()->route('quan-ly-san')->with('success', 'Thêm sân thành công!');
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
