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
    // Hiá»ƒn thá»‹ danh sĂ¡ch sĂ¢n
    public function index(Request $request) {
        $query = Yard::with('type')->orderBy('name', 'asc');

        // Kiá»ƒm tra náº¿u cĂ³ filter theo thá»ƒ loáº¡i sĂ¢n
        if ($request->has('type_id') && $request->type_id != '') {
            $query->where('type_id', $request->type_id);
        }

        $yards = $query->get();
        $types = Type::all(); // Láº¥y táº¥t cáº£ thá»ƒ loáº¡i sĂ¢n Ä‘á»ƒ hiá»ƒn thá»‹ trong dropdown

        return view('admin.yards.index', compact('yards', 'types'));
    }

    // Hiá»ƒn thá»‹ form thĂªm sĂ¢n
    public function create() {
        $types = Type::orderBy('name', 'asc')->get();
        return view('admin.yards.create', compact('types'));
    }

    // LÆ°u sĂ¢n má»›i
    public function store(StoreRequest $request) {
        if (Yard::where('name', $request->name)->exists()) {
            return redirect()->back()->with('error', 'TĂªn sĂ¢n Ä‘Ă£ tá»“n táº¡i, vui lĂ²ng nháº­p láº¡i tĂªn sĂ¢n khĂ¡c.');
        }

        Yard::create([
            'type_id' => $request->type_id,
            'name' => $request->name,
        ]);

        return redirect()->route('quan-ly-san')->with('success', 'ÄĂ£ thĂªm sĂ¢n thĂ nh cĂ´ng.');
    }

    // Hiá»ƒn thá»‹ form chá»‰nh sá»­a sĂ¢n
    public function edit($yard_id) {
        $yard = Yard::findOrFail($yard_id);
        $types = Type::orderBy('name', 'asc')->get();
        return view('admin.yards.update', compact('yard', 'types'));
    }

    // Cáº­p nháº­t thĂ´ng tin sĂ¢n
    public function update(UpdateRequest $request, $yard_id) {
        $yard = Yard::findOrFail($yard_id);

        // Kiá»ƒm tra trĂ¹ng tĂªn ngoáº¡i trá»« chĂ­nh nĂ³
        if (Yard::where('name', $request->name)->where('yard_id', '!=', $yard_id)->exists()) {
            return redirect()->back()->with('error', 'TĂªn sĂ¢n Ä‘Ă£ tá»“n táº¡i, vui lĂ²ng nháº­p láº¡i tĂªn sĂ¢n khĂ¡c.');
        }

        $yard->update([
            'type_id' => $request->type_id,
            'name' => $request->name,
        ]);

        return redirect()->route('quan-ly-san', ['type_id' => $request->type_id])->with('success', 'ÄĂ£ cáº­p nháº­t sĂ¢n thĂ nh cĂ´ng.');
    }

    // XĂ³a sĂ¢n
    public function delete($yard_id, Request $request) {
        $yard = Yard::findOrFail($yard_id);
        $yard->delete();

        return redirect()->route('quan-ly-san', ['type_id' => $request->type_id])->with('success', 'ÄĂ£ xĂ³a sĂ¢n thĂ nh cĂ´ng.');
    }
}
