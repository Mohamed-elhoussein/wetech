<?php


namespace App\Http\Filters;

class PaymentFilter extends Filter {

    public function status(?string $status = null ) {
        if ($status) {
            $this->builder->wherePaid($status === 'paid');
        }

        return $this->builder;
    }

    public function q(?string $value = null)
    {
        if ($value) {
            $this->builder->whereHas('user', function ($q) use ($value) {
                $q->where('username', 'like', "%$value%");
            });
        }

        return $this->builder;
    }

}
