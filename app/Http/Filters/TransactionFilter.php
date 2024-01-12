<?php

namespace App\Http\Filters;


class TransactionFilter extends Filter {

    public function q(?string $value = null)
    {
        if ($value) {
            $this->builder->whereHas('user', function ($q) use ($value) {
                $q->where('username', 'like', "%$value%");
            })
            ->orWhereHas('order', function ($q) use ($value) {
                $q->whereHas('provider_service', function ($q) use ($value) {
                    $q->where('title', 'like', "%$value%");
                });
            })
            ;
        }

        return $this->builder;
    }


    public function type(?string $type = null)
    {
        if($type) {
            $this->builder->whereType($type);
        }

        return $this->builder;
    }
}
