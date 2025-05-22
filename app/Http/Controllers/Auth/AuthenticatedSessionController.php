<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Cart;
use App\Models\User;
use Session;


class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
        public function store(LoginRequest $request): RedirectResponse
        {
            $request->authenticate();

            // Retrieve the logged-in user
            $user = $request->user();

            // Check if the user's email is verified
            if (!$user->hasVerifiedEmail()) {
                return redirect()->route('verification.notice')->with('error', 'Please verify your email before proceeding.');
            }

            // Retrieve the session ID
            $sessionId = $request->session()->getId();

            // Check if there's a cart in the session
            $guestCart = Session::get('cart');

            if ($guestCart) {
                foreach ($guestCart as $item) {
                    // Update or create cart items for the logged-in user
                    Cart::updateOrCreate(
                        [
                            'product_id' => $item['id'],
                            'user_id' => $user->id,
                        ],
                        [
                            'quantity' => $item['quantity'],
                            'size' => $item['size'],
                            'total' => $item['total'],
                            'session_id' => $sessionId, // Optional, if session tracking is needed
                        ]
                    );
                }

                // Delete existing cart records for the guest session
                Cart::where('session_id', $sessionId)
                    ->whereNull('user_id')
                    ->delete();

                // Clear the guest cart from the session
                Session::forget('cart');
            }

            // Regenerate session to prevent session fixation attacks
            $request->session()->regenerate();

            return redirect('/');
        }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
