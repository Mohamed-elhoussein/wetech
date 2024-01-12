<?php

namespace App\Http\BulkActions;

use App\Models\Reports;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ReportBulkAction extends BulkAction {

    public function status($status, $ids)
    {
        $solved = $status == "solved";
        $this->builder->whereIn('id', $ids)->update([
            'solved' => $solved
        ]);
    }

    public function delete($ids)
    {
        $this->builder->whereIn('id', $ids)->delete();
    }
}
