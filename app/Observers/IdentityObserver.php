<?php

namespace App\Observers;

use App\Models\Identity;

class IdentityObserver
{
    /**
     * Handle the Identity "created" event.
     *
     * @param  \App\Models\Identity  $identity
     * @return void
     */
    public function creating(Identity $identity)
    {
        $identity->user_id = auth()->user()->id;
    }
}
