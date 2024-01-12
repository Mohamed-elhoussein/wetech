<?php

namespace App\Http\Filters;

class ProductCategoryFilter extends Filter {
    public function q($value = null) {
        if ($value) {
            $this->builder->where('name', 'like', "%$value%")->orWhere('name_en', 'like', "%$value%");
        }
    }
}
