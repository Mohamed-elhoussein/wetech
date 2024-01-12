<?php

namespace App\Concerns;

use App\Http\BulkActions\BulkAction;
use Illuminate\Database\Eloquent\Builder;

trait HasBulkAction
{
    public function scopeBulkAction(Builder $query, BulkAction $bulkAction)
    {
        return $bulkAction->apply($query);
    }
}
