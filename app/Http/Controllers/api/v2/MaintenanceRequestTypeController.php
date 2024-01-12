<?php

namespace App\Http\Controllers\api\V2;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceRequestType;
use Illuminate\Http\Request;

class MaintenanceRequestTypeController extends Controller
{
    public function index()
    {
        return response()->data(
            MaintenanceRequestType::all()
        );
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        MaintenanceRequestType::create($data);

        return response()->data([
            'success' => true
        ]);
    }

    public function update(Request $request, MaintenanceRequestType $type) {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $type->update($data);

        return response()->data([
            'success' => true
        ]);
    }

    public function delete(MaintenanceRequestType $type) {
        $type->delete();

        return response()->data([
            'success' => true
        ]);
    }
}
