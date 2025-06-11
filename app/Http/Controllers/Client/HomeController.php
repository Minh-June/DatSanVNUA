<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Yard;
use App\Models\Image;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // LÃ¡ÂºÂ¥y tÃ¡ÂºÂ¥t cÃ¡ÂºÂ£ sÄ‚Â¢n kÄ‚Â¨m theo loÃ¡ÂºÂ¡i sÄ‚Â¢n (type)
        $yards = Yard::with('type')->get();

        // NhÄ‚Â³m cÄ‚Â¡c sÄ‚Â¢n theo tÄ‚Âªn loÃ¡ÂºÂ¡i sÄ‚Â¢n (type.name)
        $groupedYards = $yards->groupBy(function ($yard) {
            return $yard->type->name;
        });

        return view('view')->with('groupedYards', $groupedYards);
    }

    public function home()
    {
        if (!Auth::check()) {
            return redirect()->route('dang-nhap')->with('alert', 'YÄ‚Âªu cÃ¡ÂºÂ§u Ã„â€˜Ã„Æ’ng nhÃ¡ÂºÂ­p');
        }

        // LÃ¡ÂºÂ¥y sÄ‚Â¢n kÄ‚Â¨m loÃ¡ÂºÂ¡i vÄ‚Â  Ã¡ÂºÂ£nh
        $yards = Yard::with('type', 'images')->orderBy('yard_id')->get();

        // GÄ‚Â¡n Ã¡ÂºÂ£nh Ã„â€˜Ã¡ÂºÂ§u tiÄ‚Âªn cho mÃ¡Â»â€”i sÄ‚Â¢n
        foreach ($yards as $yard) {
            $yard->first_image_url = $yard->images->first()?->url ?? asset('image/football.jpg');
        }

        // NhÄ‚Â³m sÄ‚Â¢n theo loÃ¡ÂºÂ¡i
        $groupedYards = $yards->groupBy(fn($yard) => $yard->type->name);

        // LÃ¡ÂºÂ¥y orders trong session
        $orders = session('orders') ?? [];

        // LÃ¡ÂºÂ¥y danh sÄ‚Â¡ch yard_id trong orders
        $yardIds = collect($orders)->pluck('yard_id')->unique();

        // LÃ¡ÂºÂ¥y Ã¡ÂºÂ£nh Ã„â€˜Ã¡ÂºÂ§u tiÄ‚Âªn tÃ¡Â»Â«ng sÄ‚Â¢n trong orders
        $yardFirstImages = Image::whereIn('yard_id', $yardIds)->get()
            ->groupBy('yard_id')
            ->map(fn($imgs) => $imgs->first()?->url ?? asset('image/football.jpg'));

        return view('client.home', [
            'groupedYards' => $groupedYards,
            'user_id' => Auth::id(),
            'orders' => $orders,
            'yardFirstImages' => $yardFirstImages,
        ]);
    }
    
}
