<?php

namespace App\Http\BulkActions;

use App\Models\Reports;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SliderBulkAction extends BulkAction {

    public function status($status, $ids)
    {
        $solved = $status == "active";
        $this->builder->whereIn('id', $ids)->update([
            'active' => $solved
        ]);
    }

    public function delete($ids)
    {
        $this->builder->whereIn('id', $ids)->delete();
    }
}
