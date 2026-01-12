<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\News\StoreRequest;
use App\Http\Requests\Admin\News\UpdateRequest;
use App\Models\News;
use App\Models\User;
use App\Models\NewsContent;
use App\Models\NewsType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = News::with(['type', 'user', 'contents']);

        // Quyền truy cập theo role
        if ($user) {
            if ($user->role == 0) { // Admin
                $query->whereIn('user_id', function($q) {
                    $q->select('user_id')->from('users')->whereIn('role', [0,2,3]);
                });
            } elseif ($user->role == 2) { // Chủ thầu
                $query->where(function($q) use ($user){
                    $q->where('user_id', $user->user_id)
                    ->orWhereIn('user_id', function($q2) use ($user){
                        $q2->select('user_id')->from('users')->where('manager_id', $user->user_id);
                    });
                });
            } elseif ($user->role == 3) { // Nhân viên
                $query->where(function($q) use ($user){
                    $q->where('user_id', $user->user_id)
                    ->orWhere('user_id', $user->manager_id);
                });
            }
        }

        // Tìm kiếm theo từ khóa
        if ($request->filled('search')) {
            $search = trim($request->search);
            if (mb_stripos('đối tác đăng', $search) !== false) {
                $query->where('user_id', '!=', $user->user_id);
            } else {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhereHas('type', fn($type) => $type->where('name', 'LIKE', "%{$search}%"))
                    ->orWhereHas('user', fn($u) => $u->where('fullname', 'LIKE', "%{$search}%")
                                                        ->orWhere('phonenb', 'LIKE', "%{$search}%"));
                });
            }
        }

        // Tìm kiếm theo ngày
        if ($request->filled('date')) {
            $date = Carbon::parse($request->date)->format('Y-m-d');
        } else {
            // Mặc định là hôm nay
            $date = Carbon::today()->format('Y-m-d');
        }
        $query->whereDate('post_at', $date);

        $newsList = $query->get()->sortByDesc(function($news) use ($user){
            return $news->user_id == $user->user_id ? 1 : 0;
        });

        return view('admin.news.index', compact('newsList', 'date'));
    }

    public function create()
    {
        $user = auth()->user();
        $types = NewsType::where('user_id', $user->user_id)->orderBy('name')->get();
        return view('admin.news.create', compact('types'));
    }

    public function store(StoreRequest $request)
    {
        $userId = auth()->id();
        $postAt = date('Y-m-d');

        $news = News::create([
            'title' => $request->title,
            'news_type_id' => $request->news_type_id,
            'user_id' => $userId,
            'status' => 0,
            'post_at' => $postAt,
        ]);

        foreach ($request->content as $index => $contentText) {
            $hasImage = $request->hasFile("image.$index");
            $hasNote = !empty($request->note[$index]);
            if (empty($contentText) && !$hasImage && !$hasNote) continue;

            $contentData = [
                'news_id' => $news->news_id,
                'content' => $contentText ?: null,
                'note' => $request->note[$index] ?? null,
            ];

            if ($hasImage) {
                $file = $request->file("image.$index");
                $filename = time().'_'.$file->getClientOriginalName();
                $file->move(public_path('uploads/news'), $filename);
                $contentData['image'] = 'uploads/news/' . $filename;
            }

            NewsContent::create($contentData);
        }

        return redirect()->route('quan-ly-tin-tuc')->with('success', 'Đã thêm tin tức mới !');
    }

    public function edit($news_id)
    {
        $news = News::with('contents')->findOrFail($news_id);
        $user = auth()->user();

        if ($user->role == 3) {
            $types = NewsType::where('user_id', $user->user_id)
                ->orWhere('user_id', $user->manager_id)->get();
        } else {
            $types = NewsType::where('user_id', $user->user_id)->get();
        }

        return view('admin.news.update', compact('news', 'types'));
    }

    public function update(UpdateRequest $request, $news_id)
    {
        $news = News::with('contents')->findOrFail($news_id);
        $news->update([
            'title' => $request->title,
            'news_type_id' => $request->news_type_id,
        ]);

        $existingContents = $news->contents;

        foreach ($request->content as $index => $text) {
            $hasImage = $request->hasFile("image.$index");
            $hasNote = !empty($request->note[$index]);
            if (empty($text) && !$hasImage && !$hasNote) continue;

            $oldContent = $existingContents[$index] ?? null;
            $data = [
                'news_id' => $news->news_id,
                'content' => $text ?: null,
                'note' => $request->note[$index] ?? null,
            ];

            if ($hasImage) {
                $file = $request->file("image.$index");
                $filename = time().'_'.$file->getClientOriginalName();
                $file->move(public_path('uploads/news'), $filename);
                $data['image'] = 'uploads/news/' . $filename;
            } else {
                $data['image'] = $oldContent->image ?? null;
            }

            if ($oldContent) $oldContent->update($data);
            else NewsContent::create($data);
        }

        if (count($request->content) < $existingContents->count()) {
            $news->contents()
                ->skip(count($request->content))
                ->take($existingContents->count() - count($request->content))
                ->delete();
        }

        return redirect()->route('quan-ly-tin-tuc')->with('success', 'Cập nhật tin tức thành công !');
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'news_id' => 'required|exists:news,news_id',
            'status' => 'required|in:0,1',
        ]);

        $news = News::findOrFail($request->news_id);
        $news->update(['status' => $request->status]);
        return redirect()->route('quan-ly-tin-tuc')->with('success', 'Cập nhật trạng thái thành công !');
    }

    public function delete($news_id)
    {
        $news = News::findOrFail($news_id);
        foreach ($news->contents as $content) {
            if ($content->image && File::exists(public_path($content->image))) {
                File::delete(public_path($content->image));
            }
        }
        $news->delete();
        return redirect()->route('quan-ly-tin-tuc')->with('success', 'Xóa tin tức thành công !');
    }

    public function deleteContent($id)
    {
        $content = NewsContent::find($id);
        if ($content) {
            if ($content->image && File::exists(public_path($content->image))) {
                File::delete(public_path($content->image));
            }
            $content->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }

    public function deleteImage($id)
    {
        $content = NewsContent::find($id);
        if ($content && $content->image && File::exists(public_path($content->image))) {
            File::delete(public_path($content->image));
            $content->update(['image' => null, 'note' => null]);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }
}
