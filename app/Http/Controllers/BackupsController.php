<?php

namespace App\Http\Controllers;

use App\Enums\Permissions;
use Illuminate\Support\Facades\File; 

class BackupsController extends Controller
{
    public function index()
    {
        

        if (!File::exists(storage_path('app/backups'))) {
            File::makeDirectory(storage_path('app/backups'), 0777, true, true);
        }

        $backupsFiles = File::files(storage_path('app/backups'));
        $backups = [];

        foreach ($backupsFiles as $backupFile) {
            array_push($backups, [
                'date' => \Carbon\Carbon::parse($backupFile->getMTime())->toDateString(),
                'time' => \Carbon\Carbon::parse($backupFile->getMTime())->toTimeString(),
                'name' => $backupFile->getFileName(),
            ]);
        }

        return view('backups', compact('backups'));
    }

    public function delete(string $backup)
    {   
       
        $deleted = File::delete(storage_path('app/backups/' . $backup));
        if($deleted)return redirect()->back()->with('success','the buckup was deleted');
        else  return redirect()->back()->with('error','We encountered a problem with the deletion, please check');
  
    }
}
