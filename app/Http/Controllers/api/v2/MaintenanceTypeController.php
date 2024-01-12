<?php

namespace App\Http\Controllers\api\v2;

use App\Http\Controllers\Controller;
use App\Http\Resources\MaintenanceRequestTypeResource;
use App\Models\MaintenanceType;
use Illuminate\Http\Request;

class MaintenanceTypeController extends Controller
{
    public function index()
    {
        $types = MaintenanceType::query()
            ->latest('id')
            ->get()
        ;

        return response()->data(
            MaintenanceRequestTypeResource::collection($types)
        );
    }

    public function store()
    {
        $data = request()->validate([
            'maintenance_request_id' => 'required|numeric|exists:maintenance_requests,id',
            'type_id' => 'required|numeric|exists:maintenance_request_types,id',
            'price' => 'required|numeric'
        ]);

        $type = MaintenanceType::create($data);

        return response()->data(
            new MaintenanceRequestTypeResource($type)
        );
    }

    public function update(MaintenanceType $type)
    {
        $data = request()->validate([
            'maintenance_request_id' => 'required|numeric|exists:maintenance_requests,id',
            'type_id' => 'required|numeric|exists:maintenance_request_types,id',
            'price' => 'required|numeric'
        ]);

        $type->update($data);

        return response()->data(
            new MaintenanceRequestTypeResource($type)
        );
    }

    public function destroy(MaintenanceType $type)
    {
        $type->delete();

        return response()->data([
            'success' => true
        ]);
    }
}
