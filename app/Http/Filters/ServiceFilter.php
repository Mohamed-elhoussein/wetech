<?php

namespace App\Http\Filters;

class ServiceFilter extends Filter {

    public function status($status = null) {
        if ($status) {
            $this->builder->whereActive($status === "active");
        }

        return $this->builder;
    }

}
