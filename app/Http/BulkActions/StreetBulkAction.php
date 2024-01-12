<?php

namespace App\Http\BulkActions;


class StreetBulkAction extends BulkAction {
    public function delete($ids)
    {
        $this->builder->whereIn('id', $ids)->delete();
    }
}
