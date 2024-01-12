<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::query()->paginate();

        return view('brands.index', compact('brands'));
    }

    public function create()
    {
        return view('brands.create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Brand::create($data);

        if (request()->url)
            return redirect(request()->url);

        return redirect()->route('main.brands.index');
    }

    public function edit(Brand $brand)
    {
        return view('brands.edit', compact('brand'));
    }

    public function update(Brand $brand, Request $request) {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $brand->update($data);

        if (request()->url)
            return redirect(request()->url);

        return redirect()->route('main.brands.index');
    }

    public function destroy(Brand $brand) {
        $brand->delete();

        return redirect()->route('main.brands.index');
    }

    public function create_ajax()
    {
        return view('brands.modal');
    }
}
