<?php

namespace App\Http\BulkActions;

use App\Enum\ProductStatus;
use App\Models\Reports;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProductBulkAction extends BulkAction {

    public function status($status, $ids)
    {
        if (in_array(trim($status), ProductStatus::toArray())) {
            $this->builder->whereIn('id', $ids)->update([
                'status' => $status
            ]);
        }
    }

    public function delete($ids)
    {
        $this->builder->whereIn('id', $ids)->delete();
    }
}
