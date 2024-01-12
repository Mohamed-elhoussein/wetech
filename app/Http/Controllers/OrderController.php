<?php

namespace App\Http\Controllers;

use App\Exports\OrdersExport;
use App\Http\Filters\OrderFilter;
use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    public function index(OrderFilter $orderFilter)
    {

        $orders = Order::with('provider:id,username,country_id', 'user:id,username', 'provider_service:id,title', 'provider.country')
            ->filter($orderFilter)
            ->latest()->paginate(request()->get('limit', 15))->withQueryString()->withQueryString();

        $orders->getCollection()->transform(function ($item) {
            $item->provider_service->title = ($item->provider_service->title === Null ? optional(get_title(6, $item->provider_service))->name : $item->provider_service->title);
            return $item;
        });

        $ordersGroupedByStatus = Order::select(DB::raw('status, count(*) as total'))
        ->groupBy('status')
        ->filter($orderFilter)
        ->get()
        ->mapWithKeys(function ($order) {
            return [
                $order->status => $order->total
            ];
        })->toArray();

        return view('orders.index', compact('orders', 'ordersGroupedByStatus'));
    }



    public function create()
    {


        $providers  = User::providers();
        $users      = User::users();


        return view('orders.create', compact('providers', 'users'));
    }
    public function     store(Request $request)
    {
        $this->Valid($request);

        Order::create([
            'user_id' => $request->user_id,
            'provider_id' => $request->provider_id,
            'service_id' => $request->service_id,
            'price' => $request->price
        ]);


        return redirect()->route('orders.index');
    }
    public function edit($id)
    {
        $order =   Order::where('id', $id)->get();

        $providers  = User::providers();
        $users      = User::users();

        $order = Order::where('id', $id)->first();
        return view('orders.edit', compact('providers', 'users', 'order'));
    }
    public function update(Request $request, $id)
    {
        $this->Valid($request);
        Order::where('id', $id)->update([
            'user_id' => $request->user_id,
            'provider_id' => $request->provider_id,
            'service_id' => $request->service_id,
            'price' => $request->price
        ]);
        return redirect()->route('orders.index');
    }
    public function delete($id)
    {
        Order::findOrFail($id)->delete();
        return redirect()->route('orders.index')->with(['deleted' => 'the order was deleted']);
    }


    public function Valid($request)
    {
        $this->validate($request, [
            'user_id' => 'required',
            'provider_id' => 'required',
            'service_id' => 'required'
        ]);
    }

    public function providerServices(Request $request)
    {

        $services = Service::where('provider_id', $request->provider_id)->get(['provider_id', 'title', 'id']);

        return $services;
    }

    public function export()
    {
        return Excel::download(new OrdersExport, 'orders.xlsx');
    }
}
