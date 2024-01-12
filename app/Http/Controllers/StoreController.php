<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Cities;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductCategories;
use App\Models\ProductPayment;
use App\Models\Setting;
use App\Models\Slider;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class StoreController extends Controller
{
	use AuthenticatesUsers;

    public function authenticated()
    {
        session(['city_id' => request()->city_id]);
    }

	protected $redirectTo = '/';

	public function __construct()
	{
		$this->middleware('auth')->only('my_orders');
	}



    public function index()
	{
		$categories = ProductCategories::latest('id')->limit(5)->get();
		$top_products = Product::latest('id')->accepted()->with('category', 'provider', 'provider.country')->city(session('city_id'))->limit(12)->get();
		$product_cart_ids = auth()->check() ? auth()->user()->carts()->get()->pluck('product_id')->toArray() : [];
		$sliders = Slider::query()->where('active', 1)->whereIn('target', ['HOME', 'OfferOrProvider'])->get();

		return view('store.index', compact(
			'categories',
			'top_products',
			'sliders',
			'product_cart_ids'
		));
	}



	public function index_old()
	{
		$categories = ProductCategories::latest('id')->limit(5)->get();
		$top_products = Product::latest('id')->accepted()->with('category', 'provider', 'provider.country')->city(session('city_id'))->limit(12)->get();
		$product_cart_ids = auth()->check() ? auth()->user()->carts()->get()->pluck('product_id')->toArray() : [];
		$sliders = Slider::query()->where('active', 1)->whereIn('target', ['HOME', 'OfferOrProvider'])->get();

		return view('store.index_old', compact(
			'categories',
			'top_products',
			'sliders',
			'product_cart_ids'
		));
	}

	public function by_category(ProductCategories $category)
	{
		$products = Product::where('product_category_id', $category->id)
        ->when(request()->city_id, function ($q) {
            $q->where('city_id', request()->city_id);
        })->latest('id')->with('category', 'provider')->paginate(16);
		$product_cart_ids = auth()->check() ? auth()->user()->carts()->get()->pluck('product_id')->toArray() : [];

		return view('store.listing', compact(
			'products',
			'category',
			'product_cart_ids'
		));
	}

	public function listing_products()
	{
		$products = Product::latest('id')
        ->when(request()->city_id, function ($q) {
            $q->where('city_id', request()->city_id);
        })
        ->when(!request()->city_id, function ($q) {
            $q->where('city_id', session('city_id'));
        })
        ->with('category', 'provider')->paginate(16);
		$product_cart_ids = auth()->check() ? auth()->user()->carts()->get()->pluck('product_id')->toArray() : [];

		return view('store.listing', compact(
			'products',
			'product_cart_ids'
		));
	}

	public function confirmation(Product $product)
	{
		return view('store.confirmation', compact(
			'product'
		));
	}

	public function showLoginForm()
	{
		return view('store.login', [
            'cities' => Cities::query()->where('status', 'ACTIVE')->get()
        ]);
	}

	public function search(Request $request)
	{
		$products = collect();

		$request->whenHas('q', function () use (&$products) {
			$q = request()->q;

			if (!session('searchs')) {
				session([
					'searchs' => []
				]);
			}

			if (!in_array($q, session('searchs'))) {
				session([
					'searchs' => [...session('searchs'), $q]
				]);
			}

			$products = Product::query()->latest('id')->where('name', 'like', "%$q%")->with('category', 'provider')->paginate();
		});

		$product_cart_ids = auth()->check() ? auth()->user()->carts()->get()->pluck('product_id')->toArray() : [];
		return view('store.search', compact('products', 'product_cart_ids'));
	}

	public function my_orders()
	{
		$orders = Order::query()
			->where('user_id', auth()->id())
			->whereNotNull('product_id')
			->with('product', 'product.provider:id,username')
			->latest('id')
			->paginate(12);

		return view('store.orders', compact('orders'));
	}

	public function cart()
	{
		$carts = auth()->user()->carts()->with('product', 'product.provider:id,username')->get();
		$total_items = $carts->sum(function ($item) {
			return $item->product->price;
		});
		return view('store.cart', compact('carts', 'total_items'));
	}

	public function add_to_cart()
	{
		$data = request()->validate([
			'product_id' => 'required|numeric|exists:products,id'
		]);

		auth()->user()->carts()->firstOrCreate([
			'product_id' => $data['product_id']
		]);

		return response()->json([
			'success' => true,
			'cart_count' => auth()->user()->carts()->count()
		]);
	}

	public function remove_from_cart()
	{
		$data = request()->validate([
			'product_id' => 'required|numeric|exists:products,id'
		]);

		$cart = auth()->user()->carts()->where('product_id', $data['product_id'])->first();

		if ($cart instanceof Cart) {
			$cart->delete();
		}

		return response()->json([
			'success' => true,
			'cart_count' => auth()->user()->carts()->count()
		]);
	}

	public function order(Request $request, Product $product)
	{
		$request->validateWithBag('order', [
			'phone' => 'required|string|max:50',
			'address' => 'required|string|max:255',
			'city' => 'required|string|max:255',
			'hay' => 'required|string|max:255',
			'street' => 'required|string|max:255',
			'payment_method' => 'required|string|in:cash,credit_card',
		]);

		$fields   = $request->all();

		$user_id  = isset($fields['user_id']) ? $request->user_id : auth()->user()->id;

		$provider = User::find($product->user_id);
		$user     = User::find($user_id);

		$commission       =  Setting::get('default_commission_product')[0];

		$product_payment = ProductPayment::where('product_id', $product->id)->where('user_id', $user->id)->where('is_paid', '1')->first();

		Order::create([
			'user_id'              =>  $user->id,
			'provider_id'          =>  $provider->id,
			'product_id'           =>  $product->id,
			'product_payment_id'   =>  $product_payment->id ?? NULL,
			'price'                =>  $product->is_offer ? $product->offer_price : $product->price,
			'commission'           =>  $commission,
			'address'              =>  "العنوان: {$request->address} | المدينة: {$request->city} | الحي: {$request->hay} | الشارع: {$request->street}",
			'other_phone'          =>  convertArabicNumber($request->other_phone),
		]);

		// $title_notification  = ' المنتج: ' . $product->name . '.';
		// //  user
		// $notification         =  Notification::create([
		// 	'user_id'              =>  $user->id,
		// 	'icon'                 =>  'bell_outline_mco',
		// 	'title'                =>  'تم طلب من ' . $provider->username . $title_notification,
		// 	'message'              =>  '',
		// ]);
		// $device_token         =   $user->device_token;
		// if ($device_token) {
		// 	$fcm              =    new FCM();
		// 	$title            =    $notification->title;
		// 	$fcm->to($device_token)->message('', $title)->data('', 'order', '', $title, 'Notifications')->send();
		// }
		// // provider
		// $notification         =  Notification::create([
		// 	'user_id'              => $provider->id,
		// 	'icon'                 => 'bell_outline_mco',
		// 	'title'                => 'طلب ' . $user->username . $title_notification,
		// 	'message'              => '',
		// ]);
		// $device_token         =    $provider->device_token;
		// if ($device_token) {
		// 	$fcm              =    new FCM();
		// 	$title            =    $notification->title;
		// 	$fcm->to($device_token)->message('', $title)->data('', 'order', '', $title, 'Notifications')->send();
		// }
		// // monitor
		// if ($device_token) {

		// 	$fcm                     =      new FCM();

		// 	$un                      =      $user->username;

		// 	$observers_token  =   User::where('role', 'chat_review')->pluck('device_token')->filter()->toArray();

		// 	foreach ($observers_token as $token)
		// 		$fcm->to($token)->message('تم طلب' . $title_notification, $un . '  =>  ' . $provider->username)->data($user->id, 'info',  $title, $un . '  =>  ' . $provider->username, 'LiveChat')->send();
		// }


		$data    = ['order'  => 'created'];
		$message =  'order product was created successfully';

		if ($request->get('payment_method') == 'credit_card') {
			return response()->json([
				'redirect_url' => route('products.invoice.create', ['user_id' => $user->id, 'product_id' => $product->id]),
			]);
		}

		return response()->json([
			'redirect_url' => '/my-orders',
		]);
	}

	public function checkout(Request $request)
	{
		$carts = auth()->user()->carts()->with('product', 'product.provider:id')->get();

		$request->validate([
			'phone' => 'required|string|max:50',
			'address' => 'required|string|max:255',
			'payment_method' => 'required|string|in:cash,credit_card',
		]);


		$orders = $carts->map(function ($cart) use ($request) {
			$user  = auth()->user();
			$product = $cart->product;
			$commission       =  Setting::get('default_commission_product')[0];
			$product_payment = ProductPayment::where('product_id', $product->id)->where('user_id', $user->id)->where('is_paid', '1')->first();

			return [
				'user_id'              =>  $user->id,
				'provider_id'          =>  $product->user_id,
				'product_id'           =>  $product->id,
				'product_payment_id'   =>  $product_payment->id ?? NULL,
				'price'                =>  $product->is_offer ? $product->offer_price : $product->price,
				'commission'           =>  $commission,
				'address'              =>  $request->address,
				'other_phone'          =>  convertArabicNumber($request->other_phone),
				'created_at'   				 => now(),
				'updated_at' 					 => now(),
			];
		})->toArray();

		Order::insert($orders);

		if ($request->get('payment_method') == 'credit_card') {
			$product_ids = $carts->pluck('product_id')->unique()->toArray();
			return redirect()->route('products.invoice.order.create', ['user_id' => auth()->id(), 'products' => $product_ids]);
		}

		auth()->user()->carts()->delete();

		return redirect()->back()->with('success', 'تم الدفع بنجاح');
	}
}
