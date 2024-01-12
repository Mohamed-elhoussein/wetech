<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ModelController extends Controller
{
    public function index()
    {
        $models = Models::query()->with('brand')->paginate();

        return view('models.index', compact('models'));
    }

    public function create()
    {
        $brands = Brand::all();
        return view('models.create', compact('brands'));
    }

    public function store(Request $reuqest) {
        $data = $reuqest->validate([
            'name' => 'required|string|max:255',
            'brand_id' => 'required|numeric|exists:brands,id'
        ]);

        Models::create($data);

        if (request()->url)
            return redirect(request()->url);

        return redirect()->route('main.models.index');
    }

    public function edit(Models $model)
    {
        $brands = Brand::all();
        return view('models.edit', compact('model', 'brands'));
    }

    public function update(Models $model, Request $request) {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'brand_id' => 'required|numeric|exists:brands,id'
        ]);

        $model->update($data);

        if (request()->url)
            return redirect(request()->url);

        return redirect()->route('main.models.index');
    }

    public function destroy(Models $model) {
        $model->delete();

        return redirect()->route('main.models.index');
    }

    public function create_ajax()
    {
        return view('models.modal', [
            'brands' => Brand::all(),
        ]);
    }
}
