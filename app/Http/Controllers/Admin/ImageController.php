<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Image;
use App\Models\Yard;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\ImageYard\UpdateRequest;

class ImageController extends Controller
{
    public function index(Request $request)
    {
        $yards = Yard::orderBy('name', 'asc')->get(); // Láº¥y danh sĂ¡ch sĂ¢n

        if ($request->has('yard_id')) {
            $selectedYard = Yard::with('images')->findOrFail($request->yard_id); // Láº¥y sĂ¢n vĂ  hĂ¬nh áº£nh cá»§a sĂ¢n
            return view('admin.imgyards.index', compact('yards', 'selectedYard')); // Truyá»n dá»¯ liá»‡u sĂ¢n vĂ  hĂ¬nh áº£nh
        }

        return view('admin.imgyards.index', compact('yards')); // Tráº£ vá» khi chÆ°a chá»n sĂ¢n
    }

    public function create()
    {
        $yards = Yard::orderBy('name')->get();  // Láº¥y danh sĂ¡ch sĂ¢n
        return view('admin.imgyards.create', compact('yards'));  // Truyá»n dá»¯ liá»‡u sĂ¢n
    }

    public function store(Request $request)
    {
        $request->validate([
            'yard_id' => 'required|exists:yards,yard_id',
            'image' => 'required|image|max:2048', // Äáº£m báº£o áº£nh Ä‘Ăºng Ä‘á»‹nh dáº¡ng
        ]);
    
        // LÆ°u áº£nh vĂ o thÆ° má»¥c public (storage/app/public)
        $path = $request->file('image')->store('yards', 'public');
    
        // LÆ°u Ä‘Æ°á»ng dáº«n áº£nh vĂ o cÆ¡ sá»Ÿ dá»¯ liá»‡u
        Image::create([
            'yard_id' => $request->yard_id,
            'image' => $path,  // LÆ°u Ä‘Æ°á»ng dáº«n áº£nh
        ]);
    
        // Chuyá»ƒn hÆ°á»›ng láº¡i trang vá»›i yard_id trong URL
        return redirect()->route('quan-ly-hinh-anh-san', ['yard_id' => $request->yard_id])
                         ->with('success', 'ThĂªm hĂ¬nh áº£nh sĂ¢n thĂ nh cĂ´ng!');
    }

    public function edit($image_id)
    {
        $image = Image::findOrFail($image_id);
        return view('admin.imgyards.update', compact('image'));
    }

    public function update(UpdateRequest $request, $image_id)
    {
        $request->validate([
            'image' => 'required|image|max:2048',
        ]);

        $image = Image::findOrFail($image_id);

        // XĂ³a áº£nh cÅ©
        Storage::disk('public')->delete($image->image);

        // Upload áº£nh má»›i
        $path = $request->file('image')->store('yards', 'public');

        $image->update([
            'image' => $path,
        ]);

        return redirect()->route('quan-ly-hinh-anh-san', ['yard_id' => $image->yard_id])
                        ->with('success', 'Cáº­p nháº­t hĂ¬nh áº£nh thĂ nh cĂ´ng!');
    }

    public function delete($image_id)
    {
        $image = Image::findOrFail($image_id);
    
        Storage::disk('public')->delete($image->image);
        $image->delete();
    
        // Chuyá»ƒn hÆ°á»›ng láº¡i trang vá»›i yard_id trong URL
        return redirect()->route('quan-ly-hinh-anh-san', ['yard_id' => $image->yard_id])
                         ->with('success', 'XĂ³a hĂ¬nh áº£nh thĂ nh cĂ´ng!');
    }
}
