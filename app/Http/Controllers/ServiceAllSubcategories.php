<?php

namespace App\Http\Controllers;

use App\Models\ServiceSub2;
use App\Models\ServiceSub3;
use App\Models\ServiceSubcategories;
use Illuminate\Http\Request;

class ServiceAllSubcategories extends Controller
{
    public function  subcategories($id)
    {
        $data   =   ServiceSubcategories::where('service_categories_id', $id)->where('active', 1)->get(['id', 'name']);

        return response()->json($data);
    }
    public function  sub2($id)
    {
        $data   =   ServiceSub2::where('service_subcategories_id', $id)->where('active', 1)->get(['id', 'name']);

        return response()->json($data);
    }
    public function  sub3($id)
    {
        $data   =   ServiceSub3::where('service_sub2_id', $id)->where('active', 1)->get(['id', 'name']);

        return response()->json($data);
    }
}
