<?php


namespace App\Http\Filters;

class CityFilter extends Filter {

    public function q(?string $value = null)
    {
        if ($value) {
            $this->builder
            ->where('name', 'like', "%$value%")
            ->orWhereHas('country', function ($q) use ($value) {
                $q->where('name', 'like', "%$value%");
            });
        }

        return $this->builder;
    }

}
