<?php

namespace App\Http\Filters;

class ServiceTypeFilter extends Filter
{
    public function q(?string $value = null)
    {
        if ($value) {
            $this->builder->where('name', "like", "%$value%")
                ->orWhere("name_en", "like", "%$value%");
        }
        return $this->builder;
    }
}
