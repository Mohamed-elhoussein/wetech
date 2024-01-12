<?php

namespace App\Http\Filters;

use App\Enum\ReportStatus;
use App\Http\Filters\Filter;

class ReportFilter extends Filter
{
    public function status(?string $value = null)
    {
        if ($value) {
            $this->builder->whereSolved($value === ReportStatus::RESOLVED);
        }

        return $this->builder;
    }

    public function q(?string $value = null)
    {
        if ($value) {
            $this->builder->where('title', 'like', "%{$value}%")
                ->orWhereHas('user', function ($q) use ($value) {
                    $q->where("username", "like", "%$value%");
                })
            ;
        }

        return $this->builder;
    }
}
