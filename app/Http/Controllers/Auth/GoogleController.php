<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class GoogleController extends Controller
{
    // Redirect to Google Login
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Handle Google Callback
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Check if user already exists
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                // Create new user
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'gender' => $googleUser->user['gender'] ?? null, // Get gender if available
                    'mobile' => null, // Google does not provide mobile number, you need to ask separately
                    'role_id' => 2, // Static role_id
                    'password' => Hash::make('password123'), // Hashed default password
                ]);
            }

            // Log in the user
            Auth::login($user);

            // Redirect to dashboard or home
            return redirect()->route('home')->with('success', 'Logged in successfully!');

        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}
