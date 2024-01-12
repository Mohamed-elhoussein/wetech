<?php

namespace App\Http\Filters;

class RatingFilter extends Filter
{
    public function stars($number = null)
    {
        if ($number) {
            $this->builder->where('stars', $number);
        }
        return $this->builder;
    }
}
