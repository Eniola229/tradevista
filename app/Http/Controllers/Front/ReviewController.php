<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display orders with products pending review.
     */
    public function pendingReviews()
    {
        // Get all orders for the logged-in user with their products
        $orders = Order::where('user_id', Auth::id())->with('products')->get();

        // For each order, filter out products that already have a review.
        $pendingOrders = [];
        foreach ($orders as $order) {
            // Get products from this order that have not been reviewed by the user.
            $pendingProducts = $order->products->filter(function ($product) use ($order) {
                return !Review::where('order_id', $order->id)
                              ->where('product_id', $product->id)
                              ->where('user_id', Auth::id())
                              ->exists();
            });

            // Only include orders that have pending products.
            if ($pendingProducts->isNotEmpty()) {
                // Attach the pending products to the order.
                $order->pendingProducts = $pendingProducts;
                $pendingOrders[] = $order;
            }
        }

        return view('review', compact('pendingOrders'));
    }

    /**
     * Store a new review submitted via AJAX.
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id'   => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'rating'     => 'required|integer|min:1|max:5',
            'review'     => 'required|string|max:1000',
        ]);

        Review::create([
            'order_id'   => $request->order_id,
            'user_id'    => Auth::id(),
            'product_id' => $request->product_id,
            'rating'     => $request->rating,
            'review'     => $request->review,
            'status'     => 'OPEN', // Mark review as pending approval.
        ]);

        return response()->json(['message' => 'Review submitted successfully.']);
    }
}
