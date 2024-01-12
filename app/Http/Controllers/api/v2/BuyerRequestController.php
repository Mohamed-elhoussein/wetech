<?php

namespace App\Http\Controllers\api\V2;

use App\Events\BuyerRequestAccepted;
use App\Events\BuyerRequestCanceled;
use App\Events\BuyerRequestUpdates;
use App\Events\BuyerRequestNew;
use App\Http\Requests\BuyerRequestRequest;
use App\Http\Resources\BuyerRequestObserveResource;
use App\Http\Resources\BuyerRequestResource;
use App\Models\BuyerRequest;
use App\Models\CanceledBuyerRequest;
use App\Models\Cities;
use App\Models\Order;
use App\Models\ProductTypes;
use App\Models\ProviderServices;
use App\Models\Service;
use App\Models\ServiceType;
use App\Models\Street;
use App\Models\User;
use Illuminate\Http\Request;

class BuyerRequestController
{
    public function get_params()
    {
        $type = ServiceType::select('id', 'name')->where('service_id', 7)->get();
        $service = Service::select('id', 'name')->where('active', true)->get();
        $city = Cities::select('id', 'name')->where('status', 'ACTIVE')->get();
        $streets = Street::query()->with('cities:id,name')->get();
        $product_types = ProductTypes::query()->where('product_categories_id', 1)->select('id', 'name', 'name_en')->get();

        return response()->data(compact('type', 'service', 'city', 'streets', 'product_types'));
    }

    public function all(Request $request)
    {
        if (auth()->user()->role == 'provider') {
            $canceled_buyer_requests = CanceledBuyerRequest::all()->where('user_id', auth()->id())->pluck('buyer_request_id')->filter()->unique()->values()->toArray();

            $buyer_requests = BuyerRequest::query()->with([
                "service_type",
                "service",
                "city",
                "street",
                "product_type",
            ])->where('status', 'WAITING')->whereNotIn('id', $canceled_buyer_requests)->latest('id')->get();

            return response()->data(BuyerRequestResource::collection($buyer_requests));
        }


        $status         = strtolower($request->status);
        $search         = $request->search;

        $buyer_requests = BuyerRequest::query()->with([
            "service_type",
            "service",
            "city",
            "street",
            "product_type",
            "user",
            "provider",
            "canceled_buyer_request",
        ])->when($status, function ($query) use ($status) {
            return $query->where('status', $status);
        })->when($search, function ($query) use ($search) {
            return $this->search_query($query, $search);
        })->get();

        $statistics = BuyerRequest::query()
        ->selectRaw('COUNT(if(status=\'ACCEPTED\', 1, NULL)) as ACCEPTED,
            COUNT(if(status=\'CANCELED\', 1, NULL)) as CANCELED,
            COUNT(if(status=\'WAITING\', 1, NULL)) as WAITING')
        ->when($search, function ($query) use ($search) {
            return $this->search_query($query, $search);
        })->first();

        return response()->data(
            [
                'statistics'      =>  $statistics,
                'count'      =>  count($buyer_requests),
                'buyer_requests'  =>  BuyerRequestObserveResource::collection($buyer_requests)
            ]
        );
    }

    private function search_query($query, $search){
        return $query->where(function ($query) use  ($search){
            return $query->where('description', 'like', '%' . $search . '%')
                ->orWhere('id', str_replace('#', '', $search))
                ->orWhereHas('service_type', function ($query) use ($search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })->orWhereHas('city', function ($query) use ($search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })->orWhereHas('street', function ($query) use ($search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })->orWhereHas('product_type', function ($query) use ($search) {
                return $query->where('name', 'like', '%' . $search . '%');
            });
        });
    }

    public function cancel_buyer_requests(BuyerRequest $buyer_request)
    {
        CanceledBuyerRequest::query()->firstOrCreate([
            'buyer_request_id' => $buyer_request->id,
            'user_id' => auth()->id(),
        ]);

        return response()->data([
            'message' => 'تم إلغاء الطلب'
        ]);
    }

    public function store(BuyerRequestRequest $request)
    {
        $buyer_request = $request->storeBuyerRequest();

        // ارسال الاشعار لجميع المزودين بقبول الطلب
        $this->notify_providers($buyer_request);

        event(new BuyerRequestNew());

        return response()->data(new BuyerRequestResource($buyer_request));
    }

    public function delete_buyer_requests($id)
    {
        BuyerRequest::query()->where('id', $id)->delete();

        return response()->message('تم حذف الطلب الرقم ' . $id);
    }

    public function cancel(BuyerRequest $buyer_request)
    {
        $buyer_request->update(['status' => 'CANCELED']);

        event(new BuyerRequestCanceled($buyer_request));
        // event(new BuyerRequestUpdates($buyer_request));

        return response()->data(['message' => 'تم إلغاء الطلب عند جميع المزودين بنجاح']);
    }

    public function accept(BuyerRequest $buyer_request)
    {
        $provider_id  = isset(request()->provider_id) ? request()->provider_id : auth()->id();

        $buyer_request->update([
            'provider_id' => $provider_id,
            'status' => 'ACCEPTED'
        ]);

        // اضافة طلب جديد بناءاً على هذا الطلب
        $this->create_order($buyer_request);

        event(new BuyerRequestAccepted($buyer_request));
        // event(new BuyerRequestUpdates($buyer_request));

        $title   = 'تم قبول طلبك من قبل أحد المزودين';
        $message = 'سيتم التواصل معك قريباً';
        $token = User::query()->select('device_token')->where('id', $buyer_request->user_id)->first()->device_token;
        $fcm = new \App\Helpers\FCM();
        $fcm->to($token)->message($message, $title)->data('', 'accept_order', $message, $title, 'AcceptOrder')->send()->response();

        if(isset(request()->provider_id)){
            $title   = 'تم إسناد لك طلب جديد بزبون جديد';
            $message = 'يرجى تلبية إحتايجه وكسبه وكسب فلوسه بأقرب وقت';
            $token = User::query()->select('device_token')->where('id', $provider_id)->first()->device_token;
            $fcm = new \App\Helpers\FCM();
            $fcm->to($token)->message($message, $title)->data('', 'accepted_order', $message, $title, 'AcceptedOrder')->send()->response();
        }

        return response()->data(['message' => 'تم قبول الطلب بنجاح']);
    }

    public function providers()
    {
        $service = Service::select('id','order_index','name',)
        ->where('active', 1)->where('join_option', 1)->where('id', 7)
        ->with('provider_services_accepted:id,service_id,provider_id')
        ->orderBy('order_index')->get();

        $service = $service->map(function ($item) {
            return $item->provider_services_accepted->map(function ($item) {
                $item->title = $item->provider->username;
                return $item->provider;
            });
            return $item;
        })[0]->unique();

        return response()->data($service);
    }

    private function create_order(BuyerRequest $request): Order
    {
        return Order::create([
            'user_id'               => $request->user_id,
            'provider_id'           => $request->provider_id,
            'buyer_request_id'      => $request->id,
            //'provider_service_id'   => $request->provider_service_id,
        ]);
    }

    public function details(BuyerRequest $buyer_request)
    {
        return response()->data(new BuyerRequestResource($buyer_request));
    }

    private function notify_providers(BuyerRequest $request)
    {
        $fcm = new \App\Helpers\FCM();
        $tokens = User::query()
            ->select('device_token')
            ->where('role', 'provider')
            ->where('x_build_number', '>=', 25)
            ->where('is_blocked', 0)
            ->get()
            ->pluck('device_token')
            ->filter()
            ->values()
            ->toArray();

        $title   = $request->description;
        $message = 'سارع بقبول الطلب قبل أن يذهب لغيرك';

        foreach ($tokens as $token) {
            $fcm->to($token)->message($message, $title)->data('', 'new_order', $message, $title, 'NewOrder')->send()->response();
        }
    }

    public function test_notifi()
    {
        $fcm = new \App\Helpers\FCM();
        $token   = 'eDywhTuxQdq0PohDO5FTjA:APA91bGvZ3JtK27V0CKXWvBh-bFzLMfAG16TOYbZTwPK0gMi1Rhfa7jWgroQ2cdcuu0Ofiq-VDsQgG-4qUVfgHxynYmENK0FVyviggGx5tRm_S9qYYUmSsWEjMR8vuGDBR5NYrcV27ue';
        $title   = 'مشكلة البطارية تالفة';
        $message = 'سارع بقبول الطلب قبل أن يذهب لغيرك';
        $fcm->to($token)->message($message, $title)->data('', 'new_order', $message, $title, 'NewOrder')->send()->response();
    }

}
