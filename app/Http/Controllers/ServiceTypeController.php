<?php

namespace App\Http\Controllers;

use App\Http\BulkActions\ServiceTypeBulkAction;
use App\Http\Filters\ServiceTypeFilter;
use App\Http\Requests\ServiceTypeRequest;
use App\Models\ServiceType;
use Illuminate\Http\Request;

class ServiceTypeController extends Controller
{
    public function index(ServiceTypeFilter $filter)
    {
        $serviceTypes = ServiceType::filter($filter)->select('id', 'name', 'name_en')->latest('id')->paginate(request()->get('limit', null))->withQueryString();

        return view('service-types.index', compact('serviceTypes'));
    }

    public function create()
    {
        return view('service-types.create');
    }

    public function store(ServiceTypeRequest $request)
    {
        ServiceType::create($request->validated());
        return redirect()->route('service-types.index')->with('created', 'تم إضافة نوع الخدمة بنجاح');
    }

    public function edit(ServiceType $serviceType)
    {
        return view('service-types.edit', compact('serviceType'));
    }

    public function update(ServiceTypeRequest $request, ServiceType $serviceType)
    {
        $serviceType->update($request->validated());
        return redirect()->route('service-types.index')->with('updated', 'تم تحديث نوع الخدمة بنجاح');
    }

    public function destroy(ServiceType $serviceType)
    {
        $serviceType->delete();
        return redirect()->route('service-types.index')->with('deleted', 'تم حذف نوع الخدمة بنجاح');
    }

    public function bulkAction(ServiceTypeBulkAction $bulkAction)
    {
        ServiceType::bulkAction($bulkAction);
        return redirect()->route('service-types.index');
    }
}
