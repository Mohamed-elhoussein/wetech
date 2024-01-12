<?php


namespace App\Http\Filters;

class SliderFilter extends Filter {
    public function status(?string $status = null ) {
        if ($status) {
            $this->builder->whereActive($status === 'active');
        }

        return $this->builder;
    }
}
