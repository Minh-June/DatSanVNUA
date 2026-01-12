<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsType;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewsBoardController extends Controller
{
    public function index(Request $request)
    {
        // Dropdown: mỗi loại tin chỉ 1 lần
        $newsTypes = NewsType::select(
                DB::raw('MIN(news_type_id) as news_type_id'),
                'name'
            )
            ->groupBy('name')
            ->get();

        $query = News::with(['type', 'user', 'contents'])
            ->where('status', 0)
            ->orderByDesc('post_at'); // Sắp xếp theo cột post_at giảm dần

        // LỌC THEO TÊN LOẠI
        if ($request->filled('news_type_id')) {
            $typeName = NewsType::where('news_type_id', $request->news_type_id)
                ->value('name');

            $query->whereHas('type', function ($q) use ($typeName) {
                $q->where('name', $typeName);
            });
        }

        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        $newsList = $query->paginate(5)->withQueryString();

        return view('client.newsboard', compact('newsList', 'newsTypes'));
    }

    public function show($id)
    {
        $news = News::with(['type', 'user', 'contents'])->findOrFail($id);

        // Lấy tin liên quan
        $relatedNews = News::with('contents')
            ->where('news_type_id', $news->news_type_id)
            ->where('news_id', '!=', $id)
            ->orderByDesc('post_at')
            ->take(2)
            ->get();

        if ($relatedNews->count() < 3) {
            $needed = 3 - $relatedNews->count();
            $additionalNews = News::with('contents')
                ->where('news_id', '!=', $id)
                ->whereNotIn('news_id', $relatedNews->pluck('news_id'))
                ->orderByDesc('post_at')
                ->take($needed)
                ->get();
            $relatedNews = $relatedNews->concat($additionalNews);
        }

        // Lấy keyword từ tên loại tin
        $keyword = $news->type->name;

        // Lấy 3 shop ngẫu nhiên
        $shops = Store::inRandomOrder()->take(3)->get();

        $relatedProducts = collect();

        foreach ($shops as $shop) {
            // Lấy sản phẩm có keyword trong tên
            $product = $shop->products()
                ->where('name', 'like', '%' . $keyword . '%')
                ->with('sizes', 'images', 'store.user')
                ->first(); // chỉ lấy 1 sản phẩm để hiển thị

            if ($product) {
                // Nếu có nhiều size, chọn size có giá thấp nhất
                if ($product->sizes->count() > 0) {
                    $minSize = $product->sizes->sortBy('price')->first();
                    $product->price = $minSize->price; // gán giá hiển thị là giá thấp nhất
                }

                $relatedProducts->push($product);
            }
        }

        return view('client.newsdetail', compact('news', 'relatedNews', 'relatedProducts'));
    }
}
