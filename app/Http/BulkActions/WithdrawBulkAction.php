<?php

namespace App\Http\BulkActions;

class WithdrawBulkAction extends BulkAction
{

    public function status($status, $ids)
    {
        $this->builder->whereIn('id', $ids)->update([
            'is_confirmed' => $status === 'confirm'
        ]);
    }

    public function delete($ids)
    {
        $this->builder->whereIn('id', $ids)->delete();
    }
}
