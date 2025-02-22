<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Coupon;  // Import Coupon Model
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    // Apply coupon logic
    public function applyCoupon(Request $request)
    {
        Log::info('Received Coupon Request', ['coupon' => $request->coupon, 'price' => $request->price]);

        // Validate request
        $request->validate([
            'coupon' => 'required|string',
            'price' => 'required|numeric|min:1'
        ]);

        $couponCode = strtoupper($request->coupon);
        $originalPrice = $request->price;

        // Check if coupon exists and is active
        $coupon = Coupon::where('name', $couponCode)->first();

        if (!$coupon) {
            return response()->json(["error" => "Invalid or expired coupon"], 400);
        }

        $discountPercentage = $coupon->discount;
        $discountAmount = ($originalPrice * $discountPercentage) / 100;
        $discountedPrice = max(0, $originalPrice - $discountAmount);

        Log::info('Coupon Applied', [
            'original_price' => $originalPrice,
            'discount_percentage' => $discountPercentage,
            'discount_amount' => $discountAmount,
            'final_price' => $discountedPrice
        ]);

        return response()->json([
            "discounted_price" => $discountedPrice,
            "message" => "Coupon applied! You saved ₹" . number_format($discountAmount, 2)
        ]);
    }

    // Process Stripe Payment
    // Process Stripe Payment
    public function processPayment(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));


        $finalAmount = $request->price; 
        

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'inr',
                        'product_data' => [
                            'name' => 'Image Download',
                        ],
                        'unit_amount' => $finalAmount * 100, 
                    ],
                    'quantity' => 1,
                ]
            ],
            'mode' => 'payment',
            'success_url' => route('payment.success', ['image_url' => $request->image_url]),
            'cancel_url' => url('/'),
        ]);

        return response()->json(['redirect_url' => $session->url]);
    }


    // Success Page (Download Image)
    public function successPage(Request $request)
    {
        return view('pages.successdownload', ['image_url' => $request->image_url]);
    }
}
