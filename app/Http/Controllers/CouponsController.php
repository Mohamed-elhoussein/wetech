<?php

namespace App\Http\Controllers;

use App\Models\Coupons;
use Illuminate\Http\Request;

class CouponsController extends Controller
{
    public function index()
    {
        $coupons = Coupons::all();
        return view('coupons.index', compact('coupons'));
    }
    public function create(Request $request)
    {
        $this->validate($request, ['coupon' => 'required']);

        Coupons::create([
            'coupon' => $request->coupon,
            'discount' => $request->discount,
            'expired_at' => $request->expired_at
        ]);
        return redirect()->route('coupons.index');
    }
    public function edit($id)
    {

        $coupon = Coupons::where('id', $id)->first();
        return view('coupons.edit', compact('coupon'));
    }
    public function update(Request $request, $id)
    {
        $this->validate($request, ['coupon' => 'required']);
        Coupons::where('id', $id)->update([
            'coupon' => $request->coupon,
            'discount' => $request->discount,
            'expired_at' => $request->expired_at
        ]);
        return redirect()->route('coupons.index');
    }
    public function delete($id)
    {
        Coupons::findOrFail($id)->delete();
        return redirect()->route('coupons.index');
    }
}
