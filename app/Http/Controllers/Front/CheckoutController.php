<?php
namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use App\Models\ShippingAddress;
use Auth;
use Log;
use Session;

class CheckoutController extends Controller
{
    public function index()
    {
        $sessionId = Session::getId();
        $customerId = auth()->check() ? auth()->user()->id : null;
        $customer = auth()->user();
        $address = ShippingAddress::where('user_id', $customer->id)->first();
        $user = User::where('id', $customer->id)->first();
        $deliveryAddress = "LAGOS";

        // Fetch cart items based on user authentication
        $cartItems = Cart::when($customerId, function ($query) use ($customerId) {
            return $query->where('user_id', $customerId);
        }, function ($query) use ($sessionId) {
            return $query->where('session_id', $sessionId);
        })
            ->with('product')
            ->get()
            ->unique('product_id'); // Ensure no duplicates by product_id

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('message', 'Your cart is empty.');
        }

        $countries = "NIGERIA";
        $store = "STORE";
        $cart = [];
        $subTotal = 0;
        $totalWeight = 0;

        foreach ($cartItems as $item) {
            $product = $item->product;

            // Get the seller's user_id (assuming product has a `user_id` field that represents the seller)
            $sellerId = $product->user_id;  // This is the seller's user_id for the current product

            // get seller details
            $seller = User::find($sellerId);  // Fetch seller user details

            $discountedPrice = Product::getDiscountedPrice($product->id);
            $finalPrice = ($discountedPrice > 0) ? $discountedPrice : $product->product_price;
            $itemTotal = $item->quantity * $finalPrice;

            $subTotal += $itemTotal;

            $itemWeight = $item->quantity * $product->product_weight;
            $totalWeight += $itemWeight;

            $cart[$product->id] = [
                'id' => $product->id,
                'product_name' => $product->product_name,
                'product_price' => $finalPrice,
                'quantity' => $item->quantity,
                'total' => $itemTotal,
                'image_url' => $product->image_url,
                'size' => $item->size,
                'weight' => $itemWeight,
                'seller_id' => $sellerId, // Add seller_id to cart data
                'seller_name' => $seller ? $seller->name : 'Unknown Seller', //Add seller's name
            ];
        }


        return view('checkout', compact('countries', 'cart', 'subTotal', 'totalWeight', 'store', 'deliveryAddress', 'address', 'user'));
    }




    public function success()
    {
        $countries = "NIGERIA";

        return view('checkout-success', compact('countries'));
    }
}