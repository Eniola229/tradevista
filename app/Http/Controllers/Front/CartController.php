<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Log;

class CartController extends Controller
{

    public function carts()
    {
        $user = auth()->user();
        $cart = [];
        $totalQuantity = 0;
        $totalAmount = 0;

        if ($user) {
            // User is logged in, get cart based on user_id
            $cartItems = Cart::where('user_id', $user->id)->get();
        } else {
            // User is not logged in, get cart from session (Database using session id)
            $sessionId = Session::getId();
            $cartItems = Cart::where('session_id', $sessionId)->get();
        }

        // Create a unique cart based on product_id
        foreach ($cartItems as $item) {
            // Ensure the product is not already in the cart
            if (!isset($cart[$item->product_id])) {
                // Get the discounted price
                $discountedPrice = Product::getDiscountedPrice($item->product_id);

                // Determine the final price
                $finalPrice = $discountedPrice > 0 ? $discountedPrice : $item->product->product_price;

                // Add the product to the cart
                $cart[$item->product_id] = [
                    'id' => $item->product_id,
                    'product_name' => $item->product->product_name,
                    'product_price' => $finalPrice,
                    'quantity' => $item->quantity, // Keep original quantity
                    'total' => $item->quantity * $finalPrice, // Calculate total for display
                    'image_url' => $item->product->image_url,
                    'size' => $item->size,
                ];
            }
        }

        // Count the total unique quantity and total amount for unique products
        $totalQuantity = count($cart); // Count of unique products
        $totalAmount = array_sum(array_column($cart, 'total')); // Sum of total values for unique products

        return view('carts', compact('cart', 'totalQuantity', 'totalAmount'));
    }



    public function update_increase_cart(Request $request)
    {
        $id = $request->input('product_id');

        if (Auth::check()) {
            // Update cart for logged-in users
            $user = Auth::user();
            $cart = Cart::where('user_id', $user->id)->where('product_id', $id)->first();

            if ($cart) {
                $cart->quantity += 1;
                $cart->save();
                $total = $cart->quantity * $cart->product_price; // Calculate total price
                return response()->json([
                    'message' => 'Quantity increased successfully!',
                    'quantity' => $cart->quantity,
                    'total' => $total
                ]);
            }
        } else {
            // Update cart for non-logged-in users (using session)
            $sessionId = Session::getId(); // Get the current session ID
            $cart = Cart::where('session_id', $sessionId)->where('product_id', $id)->first();

            if ($cart) {
                $cart->quantity += 1;
                $cart->save();
                $total = $cart->quantity * $cart->product_price; // Calculate total price
                return response()->json([
                    'message' => 'Quantity increased successfully!',
                    'quantity' => $cart->quantity,
                    'total' => $total
                ]);
            }
        }

        return response()->json(['message' => 'Error updating quantity!'], 400);
    }

    public function update_decrease_cart(Request $request)
    {
        $id = $request->input('product_id');

        if (Auth::check()) {
            // Update cart for logged-in users
            $user = Auth::user();
            $cart = Cart::where('user_id', $user->id)->where('product_id', $id)->first();

            if ($cart && $cart->quantity > 1) {
                $cart->quantity -= 1;
                $cart->save();
                $total = $cart->quantity * $cart->product_price; // Calculate total price
                return response()->json([
                    'message' => 'Quantity decreased successfully!',
                    'quantity' => $cart->quantity,
                    'total' => $total
                ]);
            }
        } else {
            // Update cart for non-logged-in users (using session)
            $sessionId = Session::getId(); // Get the current session ID
            $cart = Cart::where('session_id', $sessionId)->where('product_id', $id)->first();

            if ($cart) {
                $cart->quantity -= 1;
                $cart->save();
                $total = $cart->quantity * $cart->product_price; // Calculate total price
                return response()->json([
                    'message' => 'Quantity increased successfully!',
                    'quantity' => $cart->quantity,
                    'total' => $total
                ]);
            }
        }

        return response()->json(['message' => 'Error updating quantity!'], 400);
    }

    public function remove_item(Request $request)
    {
        $id = $request->input('product_id');

        if (Auth::check()) {
            // Remove item for logged-in users
            $user = Auth::user();
            Cart::where('user_id', $user->id)->where('product_id', $id)->delete();
            return response()->json(['message' => 'Item removed successfully!']);
        } else {
            // Remove item for non-logged-in users (using session)
            $sessionId = Session::getId(); // Get the current session ID
            Cart::where('session_id', $sessionId)->where('product_id', $id)->delete();
            return response()->json(['message' => 'Item removed successfully!']);
        }
    }


    public function clearCart()
    {
        $user = auth()->user();

        if ($user) {
            // Remove all cart items related to the authenticated user from the database
            Cart::where('user_id', $user->id)->delete();
        }

        // Clear cart data from the session (and Database)
        $sessionId = Session::getId(); // Get the current session ID
        Cart::where('session_id', $sessionId)->delete();
        session()->forget('cart');

        return redirect()->back()->with('status', 'Cart has been cleared successfully.');
    }

    public function checkoutIndex()
    {
        $user = Auth::user();
        $deliveryAddresses = $user->deliveryAddresses;
        $countries = Country::with('states')->get();
        $cartItems = Cart::where('user_id', auth()->id())
            ->with(['product', 'customer']) // Eager load the product and customer relationships
            ->get();

        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->product_price * $item->quantity;
        });

        $total = $subtotal;

        $selectedBillingState = old('billing_state', optional($deliveryAddresses->first())->state);
        $selectedBillingCity = old('billing_city', optional($deliveryAddresses->first())->city);

        $selectedShippingState = old('shipping_state', optional($user)->state);
        $selectedShippingCity = old('shipping_city', optional($user)->city);

        return view('front.checkout.index', compact(
            'cartItems',
            'subtotal',
            'total',
            'countries',
            'deliveryAddresses',
            'selectedBillingState',
            'selectedBillingCity',
            'selectedShippingState',
            'selectedShippingCity'
        ));
    }


    public function wishlistIndex()
    {
        return view('front.wishlist.index');
    }
}
