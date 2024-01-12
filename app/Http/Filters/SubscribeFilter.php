<?php

namespace App\Http\Filters;

class SubscribeFilter extends Filter {
    public function from(?string $value = null) {
        if ($value) {
            return $this->builder->whereDate('created_at', '>=', $value);
        }
        return $this->builder;
    }

    public function to(?string $value = null) {
        if ($value) {
            return $this->builder->whereDate('created_at', '<=', $value);
        }
        return $this->builder;
    }
}
