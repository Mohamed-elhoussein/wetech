<?php

namespace App\Http\Controllers\api\v2;

use App\Http\Controllers\Controller;
use App\Http\Resources\FeeResource;
use App\Models\Fee;
use Illuminate\Http\Request;

class FeesController extends Controller
{
    public function index()
    {
        $fees = Fee::query()->latest('id')->get();

        return response()->data(
            FeeResource::collection($fees)
        );
    }

    public function store()
    {
        $data = $this->validateRequest();

        $fee = Fee::create($data);

        return response()->data(
            new FeeResource($fee)
        );
    }

    public function update(Fee $fee)
    {
        $data = $this->validateRequest();

        $fee->update($data);

        return response()->data(
            new FeeResource($fee)
        );
    }

    public function destroy(Fee $fee)
    {
        $fee->delete();

        return response()->data([
            'success' => true
        ]);
    }

    private function validateRequest()
    {
        return request()->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'payment_method' => 'required|string|max:255|in:cash,online,both',
            'value' => 'required|numeric',
            'active' => 'required|boolean',
            'active_for' => 'required|string|in:maintenance,product,both'
        ]);
    }
}
