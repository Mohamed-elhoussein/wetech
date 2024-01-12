<?php

namespace App\Http\BulkActions;


class CityBulkAction extends BulkAction {
    public function delete($ids)
    {
        $this->builder->whereIn('id', $ids)->delete();
    }
}
