<?php

namespace App\Http\Controllers;

use App\Exports\ReportsExport;
use App\Http\BulkActions\BulkAction;
use App\Http\BulkActions\ReportBulkAction;
use App\Http\Filters\ReportFilter;
use App\Models\Reports;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Services\CsvExporterService;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class ReportsController extends Controller
{
    public function index(ReportFilter $filter)
    {
        $reports = Reports::filter($filter)->with('user:id,username')->latest()->paginate(request()->get('limit', 15))->withQueryString();

        return view('reports', compact('reports'));
    }

    public function changeStatus($id)
    {
        $reports = Reports::where('id', $id)->update(['solved' => 1]);

        return redirect()->back()->with(['update' => 'status of raport was changed']);
    }

    public function delete($id)
    {
        Reports::findOrFail($id)->delete();
        return redirect()->route('reports.index');
    }

    public function export()
    {
        return Excel::download(new ReportsExport, 'reports.xlsx');
    }

    public function bulkAction(ReportBulkAction $bulkAction)
    {
        Reports::bulkAction($bulkAction);
        Session::flash('update', 'لقد تم تحديث البلاغات');
    }
}
