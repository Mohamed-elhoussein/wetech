<?php

namespace App\Http\BulkActions;


class UserBulkAction extends BulkAction {

    public function status(?string $status = null, $ids)
    {
        if ($status && in_array($status, ["blocked", "active"])) {
            $this->builder->whereIn('id', $ids)->update([
                'is_blocked' => $status === 'blocked'
            ]);
        }
    }

    public function delete($ids)
    {
        $this->builder->whereIn('id', $ids)->delete();
    }

}
