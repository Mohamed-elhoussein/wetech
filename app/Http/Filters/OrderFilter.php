<?php

namespace App\Http\Filters;

use App\Enum\OrderStatus;
use Carbon\Carbon;

class OrderFilter extends Filter
{
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

    public function status(?string $value = null) {
        if ($value && in_array($value, OrderStatus::toArray())) {
            return $this->builder->whereStatus($value);
        }
        return $this->builder;
    }

    /**
     * Need some refactoring
     */
    public function period(?string $value = null) {
        $value = strtolower($value);
        if ($value) {
            return $this->builder
            ->when($value === 'today', function ($q) {
                $q->whereDate('created_at', '=', date('Y-m-d'));
            })->when($value === 'yesterday', function ($q) {
                $q->whereDate('created_at', '=', Carbon::now()->subDay()->format('Y-m-d'));
            })->when($value === 'current_week', function ($q) {
                $q->whereRaw('YEARWEEK(`created_at`, 1) = YEARWEEK(CURDATE(), 1)');
            })->when($value === 'previous_week', function ($q) {
                $q->whereRaw('YEARWEEK(created_at) = YEARWEEK(NOW() - INTERVAL 1 WEEK)');
            })->when($value === 'current_month', function ($q) {
                $q->whereRaw('YEAR(created_at) = YEAR(CURRENT_DATE) AND MONTH(created_at) = MONTH(CURRENT_DATE)');
            })->when($value === 'previous_month', function ($q) {
                $q->whereRaw('YEAR(created_at) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(created_at) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)');
            });
        }
        return $this->builder;
    }
}