<?php

namespace App\Http\Filters;

use function PHPUnit\Framework\isNull;

class MaintenanceFilter extends Filter
{
    public function q(?string $value = null)
    {
        if (is_null($value)) return $this->builder;

        $value = "%$value%";

        return $this->builder
            ->whereHas('service', function ($q) use ($value) {
                $q->whereLike('name', $value);
            })
            ->orWhereHas('brand', function ($q) use ($value) {
                $q->whereLike('name', $value);
            })
            ->orWhereHas('model', function ($q) use ($value) {
                $q->whereLike('name', $value);
            })
            ->orWhereHas('color', function ($q) use ($value) {
                $q->whereLike('name', $value);
            })
            ->orWhereHas('issue', function ($q) use ($value) {
                $q->whereLike('name', $value);
            })
            ->orWhereHas('country', function ($q) use ($value) {
                $q->whereLike('name', $value);
            })
            ->orWhereHas('city', function ($q) use ($value) {
                $q->whereLike('name', $value);
            })
            ->orWhereHas('street', function ($q) use ($value) {
                $q->whereLike('name', $value);
            })
        ;
    }

    public function service_id($value = null) {
        if (is_null($value)) return $this->builder;

        return $this->builder->where('service_id', $value);
    }

    public function brand_id($value = null) {
        if (is_null($value)) return $this->builder;

        return $this->builder->where('brand_id', $value);
    }

    public function model_id($value = null) {
        if (is_null($value)) return $this->builder;

        return $this->builder->where('models_id', $value);
    }
}
