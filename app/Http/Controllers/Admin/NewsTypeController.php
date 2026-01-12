<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NewsType;
use App\Models\User;

class NewsTypeController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            $types = collect();
            $allTypes = collect();
        } else {
            if ($user->role == 0 || $user->role == 2) {
                // Admin hoặc Chủ thầu → thấy loại tin của chính mình
                $query = NewsType::where('user_id', $user->user_id);
            } elseif ($user->role == 3) {
                // Nhân viên → thấy loại tin của chủ thầu quản lý
                $query = NewsType::where('user_id', $user->manager_id);
            } else {
                $query = NewsType::query()->whereRaw('0=1');
            }

            // Lọc theo type_id nếu có
            if ($request->filled('type_id')) {
                $query->where('news_type_id', $request->type_id);
            }

            $types = $query->get();
            $allTypes = $query->get(); // dùng cho dropdown
        }

        return view('admin.newstype.index', compact('types', 'allTypes'));
    }

    public function create()
    {
        return view('admin.newstype.create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        // Xác định user_id sẽ lưu
        $ownerId = ($user->role == 3) ? $user->manager_id : $user->user_id;

        $request->validate([
            'name' => 'required|string|max:255|unique:news_types,name,NULL,news_type_id,user_id,' . $ownerId,
        ]);

        NewsType::create([
            'name'    => $request->name,
            'user_id' => $ownerId,
        ]);

        return redirect()->route('quan-ly-loai-tin-tuc')
            ->with('success', 'Thêm loại tin tức thành công !');
    }

    public function edit($news_type_id)
    {
        $type = NewsType::findOrFail($news_type_id);
        return view('admin.newstype.update', compact('type'));
    }

    public function update(Request $request, $news_type_id)
    {
        $user = auth()->user();
        $ownerId = ($user->role == 3) ? $user->manager_id : $user->user_id;

        $request->validate([
            'name' => 'required|string|max:255|unique:news_types,name,' . $news_type_id . ',news_type_id,user_id,' . $ownerId,
        ]);

        $type = NewsType::findOrFail($news_type_id);
        $type->name = $request->name;
        $type->save();

        return redirect()->route('quan-ly-loai-tin-tuc')
            ->with('success', 'Cập nhật loại tin tức thành công !');
    }

    public function delete($news_type_id)
    {
        $type = NewsType::findOrFail($news_type_id);
        $type->delete();

        return redirect()->route('quan-ly-loai-tin-tuc')->with('success', 'Xóa loại tin tức thành công !');
    }
}
