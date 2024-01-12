<?php


namespace App\Http\Filters;

class FaqFilter extends Filter {

    public function q(?string $value = null)
    {
        if ($value) {
            $this->builder->where('title', 'like', "%$value%")
                ->orWhere('content', 'like', "%$value%")
            ;
        }
        return $this->builder;
    }

}
