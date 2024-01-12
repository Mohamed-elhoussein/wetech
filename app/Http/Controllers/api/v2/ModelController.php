<?php

namespace App\Http\Controllers\api\V2;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ModelController extends Controller
{
    public function index()
    {
        return response()->data(
            Models::query()
            ->when(request()->brand_id, function ($query) {
                $query->where('brand_id', request()->brand_id);
            })
            ->get()
        );
    }

    public function store(Request $reuqest) {
        $data = $reuqest->validate([
            'name' => 'required|string|max:255',
            'brand_id' => 'required|numeric|exists:brands,id'
        ]);

        Models::create($data);

        return response()->data([
            'success' => true
        ]);
    }

    public function update(Models $model)
    {
        $data = request()->validate([
            'name' => 'required|string|max:255',
            'brand_id' => 'required|numeric|exists:brands,id'
        ]);

        $model->update($data);

        return response()->data([
            'success' => true
        ]);
    }

    public function delete(Models $model)
    {
        $model->delete();

        return response()->data([
            'success' => true
        ]);
    }

}
