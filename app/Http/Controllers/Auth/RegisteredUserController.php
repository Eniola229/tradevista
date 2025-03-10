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
                // Find the user by their email
                $user_referer = User::where('email', $refererEmail)->first();

                if ($user_referer) {
                    // Increment the balance by 100
                    $user_referer->balance += 100;
                    $user_referer->save();

                     // Log payment
                    $payment = Referer::create([
                        'user_id' => $user_referer->id,
                        'referer_id' => $user->id,
                    ]);

                    // Log payment
                    $payment = Payment::create([
                        'user_id' => $user_referer->id,
                        'currency' => "NGN",
                        'amount' => 100,
                        'description' => "REFERER BONUS",
                        'payment_method' => "PAYSTACK",
                        'status' => "PAID",
                    ]);
                } 
            }


            // Fire registered event and log in user
            event(new Registered($user));
            Auth::login($user);

            // Redirect to dashboard
            return redirect()->route('dashboard');
        }

}
