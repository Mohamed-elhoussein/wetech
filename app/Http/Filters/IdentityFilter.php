<?php

namespace App\Http\Filters;

use App\Enum\IdentityStatus;

class IdentityFilter extends Filter {
    public function status(?string $status = null)
    {
        if ($status && in_array($status, IdentityStatus::toArray())) {
            $this->builder->whereStatus($status);
        }

        return $this->builder;
    }
}
