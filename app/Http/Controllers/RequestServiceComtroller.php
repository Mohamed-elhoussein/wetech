<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestService;
class RequestServiceComtroller extends Controller
{
    public function index(){
        return view('Service.RequestService');
    }
    public function data(Request $request){
        $success=RequestService::create($request->toArray());
        if($success){
            return "<div class='alert alert-success ms_service' role='alert'>
            تم ارسال طلبك بنجاح
            </div>";

        }

    }
}
