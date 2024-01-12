<?php

namespace App\Http\BulkActions;

use App\Models\Reports;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class BulkAction {

    private $request;

    protected $builder;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $builder)
    {
        $request = $this->request->request;
        $this->builder = $builder;

        if (method_exists($this, $action = $request->get('action'))) {
            $value = $request->get('value', null);

            call_user_func_array([$this, $action], array_filter([
                $value,
                explode(",", $request->get('ids'))
            ]));
        }
    }
}
