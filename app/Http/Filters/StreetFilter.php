<?php


namespace App\Http\Filters;

class StreetFilter extends Filter {

    public function q(?string $value = null)
    {
        if ($value) {
            $this->builder
            ->where('name', 'like', "%$value%")
            ->orWhereHas('cities', function ($q) use ($value) {
                $q->where('name', 'like', "%$value%");
            });
        }

        return $this->builder;
    }

}
