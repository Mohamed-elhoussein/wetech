<?php

namespace App\Http\Controllers\api\V2;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function index()
    {
        return response()->data(Color::all());
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Color::create($data);

        return response()->data([
            'success' => true
        ]);
    }

    public function update(Color $color) {
        $data = request()->validate([
            'name' => 'required|string|max:255',
        ]);

        $color->update($data);

        return response()->data([
            'success' => true
        ]);
    }

    public function delete(Color $color) {
        $color->delete();

        return response()->data([
            'success' => true
        ]);
    }
}
