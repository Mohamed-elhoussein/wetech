<?php

namespace App\Http\Filters;

class AppRateFilter extends Filter
{

    public function q(?string $username = null)
    {
        if ($username) {
            $this->builder->whereHas('user', function ($q) use ($username) {
                return $q->where('username', "like", "%$username%");
            });
        }
        return $this->builder;
    }

    public function stars($number = null)
    {
        if ($number) {
            $this->builder->where('stars', $number);
        }
        return $this->builder;
    }
}
