<?php

namespace App\Http\Controllers\api\V2;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        return response()->data(
            Brand::all()
        );
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Brand::create($data);

        return response()->data([
            'success' => true
        ]);
    }

    public function update(Request $request, Brand $brand) {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $brand->update($data);

        return response()->data([
            'success' => true
        ]);
    }

    public function delete(Brand $brand) {
        $brand->delete();

        return response()->data([
            'success' => true
        ]);
    }
}
