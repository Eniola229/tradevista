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

        $sellerShippingPrices = []; // Stores shipping price per seller

        // Keep track of sellers we have already added shipping for
        $processedSellers = [];

        foreach ($cartItems as $cartItem) {
            $product = Product::find($cartItem->product_id);

            if (!$product) {
                continue;
            }

            $seller = $product->user;

            if (!$seller) {
                continue;
            }

            // If we've already processed this seller, skip
            if (in_array($seller->id, $processedSellers)) {
                continue;
            }

            $startup = Setup::where('user_id', $seller->id)->first();

            if (!$startup) {
                continue;
            }

            $sellerState = $startup->state;

            // Check if there's a shipping price for this seller's route
            $statePrice = StatePrice::where('origin', $sellerState)
                ->where('destination', $shippingState)
                ->first();

            if ($statePrice) {
                // Store only the first shipping price per seller
                $sellerShippingPrices[$seller->id] = $statePrice->price;
                $processedSellers[] = $seller->id; // Mark seller as processed
            }
        }

        if (empty($sellerShippingPrices)) {
            return response()->json(['success' => false, 'message' => 'No shipping price found for the selected state.'], 404);
        }

        // Sum up unique seller shipping prices
        $totalShipping = array_sum($sellerShippingPrices);

        return response()->json(['success' => true, 'price' => $totalShipping]);
    }


}
