<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceRequestType;
use Illuminate\Http\Request;

class MaintenanceTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $types = MaintenanceRequestType::query()
            ->latest('id')
            ->paginate()
        ;

        return view('main.types.index', compact('types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('main.types.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = request()->validate([
            'name' => 'required|string'
        ]);

        MaintenanceRequestType::create($data);

        return redirect('/maintenance-store/types');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MaintenanceRequestType  $type
     * @return \Illuminate\Http\Response
     */
    public function show(MaintenanceRequestType $type)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MaintenanceRequestType  $type
     * @return \Illuminate\Http\Response
     */
    public function edit(MaintenanceRequestType $type)
    {
        return view('main.types.edit', compact('type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MaintenanceRequestType  $type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MaintenanceRequestType $type)
    {
        $data = request()->validate([
            'name' => 'required|string'
        ]);

        $type->update($data);

        return redirect('/maintenance-store/types');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MaintenanceRequestType  $type
     * @return \Illuminate\Http\Response
     */
    public function destroy(MaintenanceRequestType $type)
    {
        $type->delete();

        return redirect('/maintenance-store/types');
    }
}
