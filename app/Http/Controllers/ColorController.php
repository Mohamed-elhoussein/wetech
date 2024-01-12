<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function index()
    {
        $colors = Color::query()->paginate();

        return view('colors.index', compact('colors'));
    }

    public function create()
    {
        return view('colors.create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Color::create($data);

        if (request()->url)
            return redirect(request()->url);

        return redirect()->route('main.colors.index');
    }

    public function edit(Color $color)
    {
        return view('colors.edit', compact('color'));
    }

    public function update(Color $color, Request $request) {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $color->update($data);

        if (request()->url)
            return redirect(request()->url);

        return redirect()->route('main.colors.index');
    }

    public function destroy(Color $color) {
        $color->delete();

        return redirect()->route('main.colors.index');
    }

    public function create_ajax()
    {
        return view('colors.modal');
    }
}
