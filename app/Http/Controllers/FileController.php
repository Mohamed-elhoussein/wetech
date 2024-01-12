<?php

namespace App\Http\Controllers;

use App\Helpers\HttpCodes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{

    public function download( $fileName ,$place ='chat')
    {
        if($place ==  'chat'){
        $filePath = Storage::path('/chat/files/'.$fileName);

        if( ! Storage::exists('/chat/files/'.$fileName) ){

            $message  =  'This file does not exist now, we may have lost it';
            $code     =   HttpCodes::NOT_FOUND;

            return response()->error($code ,$message);
        }

         return response()->download($filePath, $fileName);
    }
        else{
             $filePath = Storage::path('/chat/files/'.$fileName);
        }

    }
}


