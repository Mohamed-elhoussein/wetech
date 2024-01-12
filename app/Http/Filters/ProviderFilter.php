<?php


namespace App\Http\Filters;

class ProviderFilter extends Filter
{
    public function status(?string $status = null)
    {
        if ($status) {
            return $this->builder->whereIsBlocked($status === 'inactive');
        }
        return $this->builder;
    }

    public function q(?string $value = null)
    {
        if ($value) {
            $this->builder->where('first_name', 'like', "%$value%")
                ->orWhere("last_name", "like", "%$value%")
                ->orWhere("number_phone", "like", "%$value%")
                ->whereRole("provider");
        }
        return $this->builder;
    }
}
