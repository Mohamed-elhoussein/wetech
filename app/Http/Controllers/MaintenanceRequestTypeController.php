<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceRequestType;
use Illuminate\Http\Request;

class MaintenanceRequestTypeController extends Controller
{
    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        MaintenanceRequestType::create($data);

        if (request()->url)
            return redirect(request()->url);

        return redirect()->route('main.issues.index');
    }

    public function create_ajax()
    {
        return view('types.modal');
    }
}
