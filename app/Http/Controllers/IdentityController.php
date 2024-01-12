<?php

namespace App\Http\Controllers;

use App\Enum\IdentityStatus;
use App\Http\Filters\IdentityFilter;
use App\Models\Identity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IdentityController extends Controller
{
    public function index(IdentityFilter $filter)
    {
        $identities = Identity::filter($filter)->with('user')->latest()->paginate(request()->get('limit', 15))->withQueryString();

        return view('identity.index', compact('identities'));
    }

    public function approve(Identity $identity)
    {
        $user = $identity->user()->first();
        if ($user->isProvider()) {
            abort(404);
        }

        $user->update([
            'identity_verified' => true
        ]);

        $identity->update([
            'status' => IdentityStatus::APPROVED
        ]);

        return redirect()->back()->with('created', 'تم التحقق من الهوية');
    }

    public function delete(Identity $identity)
    {
        $identity->update([
            'status' => IdentityStatus::DENIED
        ]);

        Storage::disk('public')->delete(Identity::STORAGE_PATH . '/' . $identity->image);

        return redirect()->back()->with('deleted', 'تم رفض الهوية');
    }
}
