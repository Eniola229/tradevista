<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderProduct;

class BusOrdersController extends Controller
{
    public function soldProducts(Request $request)
    {
        $user = auth()->user();

        // Fetch products sold by the logged-in user
        $products = OrderProduct::whereHas('product', function ($query) use ($user) {
                $query->where('user_id', $user->id); // Ensure products belong to the logged-in seller
            })
            ->with('product') // Load product details
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Paginate with 10 items per page

        $productCount = $products->count();
        return view('bus-orders', compact('products', 'productCount'));
    }
}
