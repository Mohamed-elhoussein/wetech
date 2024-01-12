<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Order;
use App\Models\ProviderServices;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use stdClass;

class StatisticController extends Controller
{
    public function index()
    {
        $now        = Carbon::now();

        $providers   =  User::toBase()
            ->selectRaw("count(case when is_blocked in (0,1) then 1 end) as alls")
            ->selectRaw("count(case when is_blocked = 0 then 1 end) as active")
            ->selectRaw("count(case when is_blocked = 1 then 1 end) as blocked")
            ->where('role', 'provider')
            ->first();

        $provider_services = ProviderServices::count('id');

        $providers->services = $provider_services;


        $offers   =  Offer::withTrashed()
            ->toBase()
            ->selectRaw("count(id) as alls")
            ->selectRaw("count(case when created_at >='" . $now->format('Y-m-d') . "' then 1 end) as today")
            ->selectRaw("count(case when created_at >='" . $now->subWeek()->format('Y-m-d') . "' then 1 end) as last_week")
            ->selectRaw("count(case when created_at >='" . $now->subMonth()->format('Y-m-d') . "' then 1 end) as last_month")
            ->first();

        $orders   =  Order::toBase()
            ->selectRaw("count(case when status = 'COMPLETED' then 1 end) as completed")
            ->selectRaw("count(case when status = 'CANCELED' then 1 end) as canceled")
            ->selectRaw("count(case when status in ('WAITING' , 'PENDING') then 1 end) as pending")
            ->first();
        $revenue   = Order::toBase()
            ->selectRaw("sum(case when status = 'COMPLETED'   then  price  end) as completed")
            ->selectRaw("sum(case when status = 'CANCELED' then price end) as canceled")
            ->selectRaw("sum(case when status in ('WAITING' , 'PENDING') then price end) as pending")
            ->first();

        // $phones = User::select('role','x_os')->get();
        $phones = [
            'user' => [
                'android' => 0,
                'ios' => 0,
            ],
            'provider' => [
                'android' => 0,
                'ios' => 0
            ],
        ];

        $phoneRes   =  User::toBase()
            ->selectRaw('x_os, role, count(*) as count')
            ->where('x_os', '!=', null)
            ->whereIn('role', ['user', 'USER', 'provider'])
            ->groupByRaw('role, x_os')
            ->get();

        foreach ($phoneRes as $res) {
            $phones[strtolower($res->role)][strtolower($res->x_os)] = $res->count;
        }

        return view('statistic', compact('providers', 'offers', 'orders', 'revenue', 'phones'));
    }
}
