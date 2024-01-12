<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\IdentityVerificationRequest;
use App\Models\Identity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IdentityVerificationController
{
    public function upload(IdentityVerificationRequest $request) {
        /** @var User $user */
        $user = Auth::user();

        if ($user->hasPendingIdentity()) {
            return response()->success('تم إرسال هويتك المرجو الإنتظار حتى تتم الموافقة');
        }

        $file = $request->file('identity');
        $ext = $file->getClientOriginalExtension();
        $fileName = time() . ".$ext";
        $file->storeAs(Identity::STORAGE_PATH, $fileName, 'public');

        Identity::create([
            'image' => $fileName
        ]);

        return response()->success('تم إرسال الهوية بنجاح !');
    }
}
