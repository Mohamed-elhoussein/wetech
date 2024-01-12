<?php

namespace App\Http\BulkActions;


class CountryBulkAction extends BulkAction {

    public function status(?string $status = null, $ids)
    {
        if ($status && in_array($status, ["ACTIVE", "UNACTIVE"])) {
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
