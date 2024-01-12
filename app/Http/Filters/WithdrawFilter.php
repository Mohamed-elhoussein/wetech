<?php


namespace App\Http\Filters;

class WithdrawFilter extends Filter {

    public function status(?string $status = null ) {
        if ($status) {
            $this->builder->whereIsConfirmed($status === 'confirmed');
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
