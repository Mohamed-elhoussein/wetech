<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class ProviderServiceFilter extends Filter {
    public function q(?string $value = null)
    {
        if ($value) {
            $this->builder->where('title', 'like', "%{$value}%")
            ->orWhereHas('provider', function (Builder $query) use ($value) {
                $query->where('username', 'like', "%{$value}%");
            })
            ;
        }

        return $this->builder;
    }

    public function provider(?string $value = null)
    {
        if ($value) {
            $this->builder->whereHas('provider', function (Builder $query) use ($value) {
                $query->where('username', 'like', "%{$value}%")
                    ->whereRole('provider')
                ;
            })
            ;
        }

        return $this->builder;
    }
}
