<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;


class WishlistController extends Controller
{
    public function wishlistIndex()
    {
        $wishlist = []; 
        $wishlistCount = 0; 

        if (Auth::check()) {
            $user = auth()->user();
            $wishlistItems = Wishlist::where('user_id', $user->id)->get();

            foreach ($wishlistItems as $item) {
                $wishlist[$item->product_id] = [
                    'id' => $item->product_id,
                    'product_name' => $item->product->product_name,
                    'product_price' => $item->product->product_price,
                    'image_url' => $item->product->image_url,
                ];
            }
            $wishlistCount = $wishlistItems->count();
        } else {
            $wishlistItems = [];
        }

        return view('wishlist', compact('wishlist', 'wishlistCount'));
    }


    public function addWishlist(Request $request)
    {
        $productid = $request->input('product_id');
        $product = Product::where('id', $productid)->first();

        if (!$product) {
            return response()->json(['status' => 'error', 'message' => 'Oops! Product not found.'], 404);
        }

        if (Auth::check()) {
            $user = auth()->user();
            $wishlistItem = Wishlist::where('user_id', $user->id)
                                    ->where('product_id', $product->id)
                                    ->first();

            if ($wishlistItem) {
                return response()->json(['status' => 'error', 'message' => 'Oops! Product already in wishlist.'], 409);
            }

            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
            ]);

            return response()->json(['status' => 'success', 'message' => 'Product added to wishlist successfully']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'You need to be logged in to save this item'], 401);
        }
    }

    public function removeWishlist(Request $request)
    {
        $id = $request->input('product_id');

        if (Auth::check()) {
            $user = Auth::user();
            Wishlist::where('user_id', $user->id)->where('product_id', $id)->delete();
            return response()->json(['message' => 'Item removed successfully!']);
        } 

        return response()->json(['message' => 'Error removing item!'], 400);
    }

    public function clearWishlist()
    {
        $user = auth()->user();

        if ($user) {
            // Remove all Wishlist items related to the authenticated user from the database
            Wishlist::where('user_id', $user->id)->delete();
        }

        return redirect()->back()->with('status', 'Wait List has been cleared successfully.');
    }

}
 