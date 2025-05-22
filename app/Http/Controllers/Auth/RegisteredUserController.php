<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Payment;
use App\Models\Referer;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
        public function store(Request $request): RedirectResponse
        {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'referer' => ['nullable', 'string', 'lowercase', 'email', 'max:255', Rule::exists('users', 'email')],
                'phone_number' => ['required', 'string', 'max:11', 'unique:'.User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            // Welcome amount (stored as a numeric value)
            $welcomeAmount = 2035.00;

            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'balance' => $welcomeAmount, // Use the numeric value
                'phone_number' => $request->phone_number,
                'password' => Hash::make($request->password),
            ]);

            // Log payment
            $payment = Payment::create([
                'user_id' => $user->id,
                'currency' => "NGN",
                'amount' => $welcomeAmount,
                'description' => "BONUS FOR WELCOME REGISTRATION",
                'payment_method' => "PAYSTACK",
                'status' => "PAID",
            ]);

            // Check if 'referer' exists in the URL
            if ($request->has('referer')) {
                $refererEmail = $request->input('referer');
                $user_referer = User::where('email', $refererEmail)->first();

                if ($user_referer) {
                    // Give ₦100 to the direct referrer (Mr B)
                    $user_referer->balance += 100;
                    $user_referer->save();

                    // Save referer relationship
                    Referer::create([
                        'user_id' => $user_referer->id,
                        'referer_id' => $user->id,
                    ]);

                    // Log payment for direct referral
                    Payment::create([
                        'user_id' => $user_referer->id,
                        'currency' => "NGN",
                        'amount' => 100,
                        'description' => "DIRECT REFERER BONUS",
                        'payment_method' => "PAYSTACK",
                        'status' => "PAID",
                    ]);

                    // Check if Mr B (the direct referrer) also has a referrer (Mr A)
                    $grand_referer = Referer::where('referer_id', $user_referer->id)->first();

                    if ($grand_referer) {
                        $grand_referer_user = User::find($grand_referer->user_id);
                        if ($grand_referer_user) {
                            // Give ₦50 to the indirect referrer (Mr A)
                            $grand_referer_user->balance += 50;
                            $grand_referer_user->save();

                            // Log payment for indirect referral
                            Payment::create([
                                'user_id' => $grand_referer_user->id,
                                'currency' => "NGN",
                                'amount' => 50,
                                'description' => "INDIRECT REFERER BONUS",
                                'payment_method' => "PAYSTACK",
                                'status' => "PAID",
                            ]);
                        }
                    }
                }
            }



            // Fire registered event and log in user
            event(new Registered($user));
            Auth::login($user);

            // Redirect to dashboard
            return redirect()->route('verification.notice')->with('message', 'Please verify your email before logging in.');
        }

}
