<?php

namespace App\Http\BulkActions;

class ServiceBulkAction extends BulkAction {

    public function status($status, $ids)
    {
        $this->builder->whereIn('id', $ids)->update([
            'active' => $status === 'active'
        ]);
    }

    public function delete($ids)
    {
        $this->builder->whereIn('id', $ids)->delete();
    }
}
