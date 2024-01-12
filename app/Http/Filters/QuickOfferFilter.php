<?php

namespace App\Http\Filters;

class QuickOfferFilter extends Filter {
    public function q(?string $value = null)
    {
        if ($value) {
            $this->builder->where("title", "like", "%$value%")
                ->orWhere("body", "like", "%$value%")
            ;
        }

        return $this->builder;
    }
}
