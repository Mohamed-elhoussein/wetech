<?php

namespace App\Http\Filters;

use App\Http\Filters\Filter;

class SkillFilter extends Filter  {

    public function q(?string $value = null)
    {
        if ($value) {
            $this->builder->where("name", "like", "%{$value}%");
        }

        return $this->builder;
    }

}
