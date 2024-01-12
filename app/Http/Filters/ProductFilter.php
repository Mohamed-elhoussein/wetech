<?php

namespace App\Http\Filters;

use App\Enum\ProductStatus;

class ProductFilter extends Filter {
    public function q(?string $value = null)
    {
        if ($value) {
            $this->builder->where("name", "like", "%$value%")->orWhere("description", "like", "%$value%");
        }

        return $this->builder;
    }

    public function status(?string $status = null)
    {
        if ($status && in_array($status, ProductStatus::toArray())) {
            $this->builder->whereStatus($status);
        }

        return $this->builder;
    }
}
