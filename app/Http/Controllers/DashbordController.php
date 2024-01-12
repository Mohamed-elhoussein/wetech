<?php

namespace App\Http\Controllers;

use App\Enum\OrderStatus;
use App\Http\BulkActions\WithdrawBulkAction;
use App\Http\Filters\AppRateFilter;
use App\Http\Filters\WithdrawFilter;
use App\Models\Order;
use App\Models\UserWithdraw;
use App\Models\Service;
use App\Models\User;
use App\Models\AppRates;
use App\Models\Transaction;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashbordController extends Controller
{
    private $statisticService;

    public function __construct()
    {
    }

    public function dashbord()
    {
        $orderCount     =   Order::toBase()->count('id');

        $statistics     =   User::toBase()
            ->selectRaw("count(id) as users")
            ->selectRaw("count(case when role = 'provider' then 1 end) as providers")
            ->get()[0];


        $services       =   Service::where('active', 1)->count('id');
        $orders =  Order::with('provider:id,username', 'user:id,username', 'provider_service:id,title')->orderBy('created_at', 'desc')->take(5)->get();
        $providers = User::provider()->with('commission', 'country:id,name,country_code')->take(10)->get();
        $transactions = Transaction::with('user:id,username', 'order:id,provider_service_id', 'order.provider_service:id,title')->take(5)->get();


        // For refactor
        // $this->$statisticService->getAdminStats();
        $period = CarbonPeriod::create(Carbon::now()->subMonth(), Carbon::now());
        $ordersCount = Order::count();

        $chartLabels = [];
        $dataOrders = DB::table('orders')
            ->selectRaw('DATE_FORMAT(created_at, "%M %d") as created_at, count(id) as total')
            ->groupByRaw('DATE_FORMAT(created_at, "%M %d")')
            ->get()
            ->toArray();
        $data = [];

        foreach ($period as $p) {
            $date = $p->format('M d');
            $chartLabels[] = $date;

            foreach ($dataOrders as $order) {
                if ($order->created_at == $date) {
                    $data[$date] = $order->total;
                } else if (!array_key_exists($date, $data)) {
                    $data[$date] = 0;
                }
            }
        }

        $orderStatus = DB::table('orders')
            ->selectRaw('status, count(id) as total')
            ->groupByRaw('status')
            ->get()
            ->toArray();
        $dataOrdersStatus = [];

        foreach (OrderStatus::toArray() as $status) {
            foreach ($orderStatus as $order) {
                if ($order->status === $status) {
                    $dataOrdersStatus[__($status)] = $order->total;
                } else if (!isset($dataOrdersStatus[__($status)])) {
                    $dataOrdersStatus[__($status)] = 0;
                }
            }
        }

        $lastLoginUsers = User::latest('last_login')->select('username', 'last_login', 'role')->where('last_login', '!=', null)->limit(9)->get();


        // App Rates Refactoring

        // Get the rates from the database
        $rates = AppRates::select('stars')->get();
        $_avgRates = AppRates::selectRaw('stars, count(id) as total')->groupBy('stars')->get();

        $avgRates = number_format($rates->reduce(function ($res, $rate) {
            return $res += $rate['stars'];
        }, 0) / $rates->count(), 1);

        $stars = [
            '5 stars' => 0,
            '4 stars' => 0,
            '3 stars' => 0,
            '2 stars' => 0,
            '1 stars' => 0,
        ];

        foreach ($_avgRates as $avg) {
            switch ($avg['stars']) {
                case 5:
                    $stars['5 stars'] = number_format($avg['total'] / $rates->count() * 100, 1);
                    break;
                case 4:
                    $stars['4 stars'] = number_format($avg['total'] / $rates->count() * 100, 1);
                    break;
                case 3:
                    $stars['3 stars'] = number_format($avg['total'] / $rates->count() * 100, 1);
                    break;
                case 2:
                    $stars['2 stars'] = number_format($avg['total'] / $rates->count() * 100, 1);
                    break;
                case 1:
                    $stars['1 stars'] = number_format($avg['total'] / $rates->count() * 100, 1);
                    break;
            }
        }

        return view('dashbord', compact('transactions', 'avgRates', 'stars', 'lastLoginUsers', 'dataOrdersStatus', 'ordersCount', 'chartLabels', 'data', 'orders', 'orderCount', 'statistics', 'services', 'providers'));
    }


    public function appRates(AppRateFilter $filter)
    {
        $rates = AppRates::filter($filter)->with('user')->paginate(request()->get('limit', 15))->withQueryString();
        return view('apprates', compact('rates'));
    }
    public function notify(Request $request)
    {
        $this->validate($request, ['title' => 'required', 'message' => 'required']);
        $tokens = [];
        switch ($request->target) {
            case 'users':
                $tokens = User::where('role', 'user')->pluck('device_token')->filter();
                break;
            case 'provider':
                $tokens = User::where('role', 'provider')->pluck('device_token')->filter();
                break;
            default:
                $tokens = User::whereIn('role', ['provider', 'user'])->pluck('device_token')->filter();
                break;
        }

        $fcm                =    new \FCM();
        foreach ($tokens as $token) {
            $fcm->to($token)->message($request->message, $request->title)->send();
        }
        return redirect()->back()->with('created', 'all notifications completed');
    }

    public function withdraw(WithdrawFilter $filter)
    {
        $payments  = UserWithdraw::filter($filter)->with('user')->paginate(request()->get('limit', 15))->withQueryString();

        return view('withdraw', compact('payments'));
    }

    public function withdrawStatus($id)
    {
        UserWithdraw::where('id', $id)->update([
            'is_confirmed' => 1
        ]);

        return redirect()->back()->with(['updated' => 'payment was confirmed ']);
    }

    public function bulkActionWithdraw(WithdrawBulkAction $withdrawBulkAction)
    {
        UserWithdraw::bulkAction($withdrawBulkAction);
    }

    public function error500(string $text)
    {
        $test = 'test';
    }
}
