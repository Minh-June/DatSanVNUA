<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Type;

class TypeController extends Controller
{
    public function index(Request $request)
    {
        // Danh sĂ¡ch Ä‘áº§y Ä‘á»§ cho dropdown
        $allTypes = Type::orderBy('name', 'asc')->get();

        // Táº¡o query Ä‘á»ƒ lá»c danh sĂ¡ch
        $query = Type::query();

        // Náº¿u chá»n loáº¡i cá»¥ thá»ƒ, lá»c theo type_id
        if ($request->filled('type_id')) {
            $query->where('type_id', $request->type_id);
        }

        // Láº¥y káº¿t quáº£ lá»c
        $types = $query->orderBy('name', 'asc')->get();

        return view('admin.types.index', compact('types', 'allTypes'));
    }

    public function create()
    {
        return view('admin.types.create');  // Äáº£m báº£o báº¡n Ä‘Ă£ cĂ³ view 'create' cho trang táº¡o má»›i
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[^\d\W_]+(?:\s[^\d\W_]+)*$/u',
            ],
        ], [
            'name.regex' => 'TĂªn loáº¡i sĂ¢n khĂ´ng Ä‘Æ°á»£c chá»©a sá»‘ hoáº·c kĂ½ tá»± Ä‘áº·c biá»‡t.',
        ]);
    
        // Kiá»ƒm tra tĂªn loáº¡i sĂ¢n Ä‘Ă£ tá»“n táº¡i chÆ°a
        $exists = Type::where('name', $request->name)->exists();
        if ($exists) {
            return redirect()->back()->with('error', 'Loáº¡i sĂ¢n Ä‘Ă£ tá»“n táº¡i, vui lĂ²ng nháº­p loáº¡i sĂ¢n má»›i.');
        }
    
        $type = new Type();
        $type->name = $request->name;
        $type->save();
    
        return redirect()->route('quan-ly-loai-san')->with('success', 'ThĂªm loáº¡i sĂ¢n thĂ nh cĂ´ng!');
    }

    // Hiá»ƒn thá»‹ form sá»­a loáº¡i sĂ¢n
    public function edit($type_id)
    {
        $type = Type::find($type_id);  // TĂ¬m loáº¡i sĂ¢n theo ID
        if (!$type) {
            return redirect()->route('quan-ly-loai-san')->with('error', 'Loáº¡i sĂ¢n khĂ´ng tá»“n táº¡i');
        }
        return view('admin.types.update', compact('type')); // Truyá»n dá»¯ liá»‡u vĂ o view Ä‘á»ƒ chá»‰nh sá»­a
    }

    // Cáº­p nháº­t loáº¡i sĂ¢n
    public function update(Request $request, $type_id)
    {
        $type = Type::find($type_id);
        if (!$type) {
            return redirect()->route('quan-ly-loai-san')->with('error', 'Loáº¡i sĂ¢n khĂ´ng tá»“n táº¡i');
        }
    
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{L}\s]+$/u', // Chá»‰ cho chá»¯ vĂ  khoáº£ng tráº¯ng
            ],
        ], [
            'name.regex' => 'TĂªn loáº¡i sĂ¢n khĂ´ng Ä‘Æ°á»£c chá»©a sá»‘ hoáº·c kĂ½ tá»± Ä‘áº·c biá»‡t.',
        ]);

    
        // Kiá»ƒm tra tĂªn loáº¡i sĂ¢n Ä‘Ă£ tá»“n táº¡i (ngoáº¡i trá»« chĂ­nh nĂ³)
        $exists = Type::where('name', $request->name)
                    ->where('type_id', '!=', $type_id)
                    ->exists();
        if ($exists) {
            return redirect()->back()->with('error', 'Loáº¡i sĂ¢n Ä‘Ă£ tá»“n táº¡i, vui lĂ²ng nháº­p loáº¡i sĂ¢n má»›i.');
        }
    
        $type->name = $request->input('name');
        $type->save();
    
        return redirect()->route('quan-ly-loai-san')->with('success', 'Cáº­p nháº­t loáº¡i sĂ¢n thĂ nh cĂ´ng');
    }

    public function delete($type_id)
    {
        $type = Type::find($type_id);  // TĂ¬m loáº¡i sĂ¢n theo ID
        if (!$type) {
            return redirect()->route('quan-ly-loai-san')->with('error', 'Loáº¡i sĂ¢n khĂ´ng tá»“n táº¡i');
        }

        $type->delete();  // XĂ³a loáº¡i sĂ¢n

        return redirect()->route('quan-ly-loai-san')->with('success', 'XĂ³a loáº¡i sĂ¢n thĂ nh cĂ´ng');
    }

}
