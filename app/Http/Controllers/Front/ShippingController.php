<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Setup;
use App\Models\StatePrice;
use Illuminate\Support\Facades\Auth;

class ShippingController extends Controller
{
      public function calculateShipping(Request $request)
    {
        $request->validate([
            'stateCode' => 'required|string|max:255'
        ]);

        $shippingState = $request->stateCode;
        $userId = Auth::id();

        // Get products in the user's cart
        $cartItems = Cart::where('user_id', $userId)->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Your cart is empty!'], 400);
        }

        $shippingPrices = [];

        foreach ($cartItems as $cartItem) {
            $product = Product::find($cartItem->product_id);

            if (!$product) {
                continue;
            }

            $seller = $product->user;

            if (!$seller) {
                continue;
            }

            $startup = Setup::where('user_id', $seller->id)->first();

            if (!$startup) {
                continue;
            }

            $sellerState = $startup->state;

            // Check if there's a shipping price for this route
            $statePrice = StatePrice::where('origin', $sellerState)
                ->where('destination', $shippingState)
                ->first();

            if ($statePrice) {
                $shippingPrices[] = $statePrice->price;
            }
        }

        if (empty($shippingPrices)) {
            return response()->json(['success' => false, 'message' => 'No shipping price found for the selected state.'], 404);
        }

        // Get the highest shipping price (assuming multiple products might have different shipping costs)
        $maxPrice = max($shippingPrices);

        return response()->json(['success' => true, 'price' => $maxPrice]);
    }
}
