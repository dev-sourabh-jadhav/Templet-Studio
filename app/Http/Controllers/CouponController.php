<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Coupon as StripeCoupon;
use App\Models\Coupon;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::all();
        return view('pages.discount', compact('coupons'));
    }

    public function create()
    {
        return view('pages.discount');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:coupons,name',
            'discount' => 'required|numeric|min:1|max:100',
            'duration' => 'required|in:forever,once,repeating',
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        $stripeCoupon = StripeCoupon::create([
            'id' => strtolower(str_replace(' ', '_', $request->name)), // Unique ID for Stripe
            'percent_off' => $request->discount,
            'duration' => $request->duration,
        ]);

        Coupon::create([
            'stripe_coupon_id' => $stripeCoupon->id,
            'name' => $request->name,
            'discount' => $request->discount,
            'duration' => $request->duration,
        ]);

        return redirect()->back()->with('success', 'Coupon Deleted Successfully');
    }

    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('coupons.edit', compact('coupon'));
    }

    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $request->validate([
            'name' => 'required|string|unique:coupons,name,' . $id,
            'discount' => 'required|numeric|min:1|max:100',
            'duration' => 'required|in:forever,once,repeating',
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        // Updating coupon in Stripe (Stripe does not allow updating all fields)
        StripeCoupon::update($coupon->stripe_coupon_id, [
            'name' => $request->name,
        ]);

        $coupon->update([
            'name' => $request->name,
            'discount' => $request->discount,
            'duration' => $request->duration,
        ]);

        return redirect()->back()->with('success', 'Coupon Deleted Successfully');
    }

    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);

        Stripe::setApiKey(config('services.stripe.secret'));

        $couponStripe = StripeCoupon::retrieve($coupon->stripe_coupon_id);
        $couponStripe->delete();

        $coupon->delete();

        return redirect()->back()->with('success', 'Coupon Deleted Successfully');
    }

}
