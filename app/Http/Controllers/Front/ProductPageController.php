<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Models\Setup;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductsAttribute;
use App\Models\ProductsImage;
use App\Models\Review;
use App\Models\Cart;
use Illuminate\Support\Facades\Http;
use Validator;
use Session;
use Illuminate\Validation\Rule;


class ProductPageController extends Controller
{
        public function viewProduct($id)
    {
        $userInfo = Auth::user();
        $product = Product::where('id', $id)->first();
        $setup = Setup::where('user_id', $userInfo->id)->first();
        $category = Category::where('id', $product->category_id)->first();
        $attribute = ProductsAttribute::where('product_id', $product->id)->first();
        $image = ProductsImage::where('product_id', $product->id)->first();
        $images = ProductsImage::where('product_id', $product->id)->get();
        $reviews = Review::where('product_id', $product->id)
                                ->with('user')
                                ->get();
        $reviewCount = Review::where('product_id', $product->id)->count();


        return view('view-product', compact('product', 'setup', 'category', 'attribute', 'image', 'images', 'reviews', 'reviewCount'));
    }

    public function ProductDetails($id)
    {
        $product = Product::where('id', $id)->first();
        $setup = Setup::where('user_id', $product->user_id)->first();
        $category = Category::where('id', $product->category_id)->first();
        $attribute = ProductsAttribute::where('product_id', $product->id)->first();
        $image = ProductsImage::where('product_id', $product->id)->first();
        $images = ProductsImage::where('product_id', $product->id)->get();
        $reviews = Review::where('product_id', $product->id)
                                ->with('user')
                                ->get();
        $reviewCount = Review::where('product_id', $product->id)->count();

        $relatedProducts = Product::where('category_id', $product->category_id)
                            ->inRandomOrder() 
                            ->take(5)   
                            ->get();


        return view('product-details', compact('product', 'setup', 'category', 'attribute', 'image', 'images', 'reviews', 'reviewCount', 'relatedProducts'));
    }

        private function updateSessionCart()
    {
        $sessionId = session()->getId();
        $cartItems = Cart::where('session_id', $sessionId)->get();
        $cart = [];

        foreach ($cartItems as $item) {
            $product = Product::find($item->product_id);
            if ($product) {
                $cart[$product->id] = [
                    'id' => $product->id,
                    'product_name' => $product->product_name,
                    'product_price' => $product->product_price,
                    'quantity' => $item->quantity,
                    'size' => $item->size,
                    'total' => $item->quantity * $product->product_price,
                    'image_url' => $product->image_url,
                    'session_id' => $sessionId,
                ];
            }
        }

        session()->put('cart', $cart);

        // Optionally update the total amount and quantity in the session
        $totalAmount = array_sum(array_column($cart, 'total'));
        session()->put('totalAmount', $totalAmount);

        $totalQuantity = array_sum(array_column($cart, 'quantity'));
        session()->put('totalQuantity', $totalQuantity);
    }


    public function addToCart(Request $request)
    {
        $productid = $request->input('product_id');
        $quantity = $request->input('quantity', 1);
        $size = $request->input('size'); // Nullable

        // Find the product by id
        $product = Product::where('id', $productid)->first();

        if (!$product) {
            return response()->json(['status' => 'error', 'message' => 'Oops! Product not found.'], 404);
        }

        if ($product->stock < 1) {
            return response()->json(['status' => 'error', 'message' => 'Sorry, this product is no longer available.'], 400);
        }

        $sessionId = $request->session()->getId();
       
        $user = Auth::user(); // Get the logged-in user

        // Create or update the cart item
        $cartItem = Cart::where('session_id', $sessionId)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            // Update quantity if item already exists
            $cartItem->quantity += $quantity;
            $cartItem->size = $size;
            $cartItem->save();
        } else {
            Cart::create([
                'session_id' => $sessionId,
                'order_code' => null,
                'user_id' => $user ? $user->id : null,
                'product_id' => $product->id,
                'size' => $size,
                'quantity' => $quantity,
            ]);
        }

        // Update session cart for immediate feedback
        $this->updateSessionCart();

        return response()->json(['status' => 'success', 'message' => 'Product added to cart successfully'. $user]);
    }

    public function ProductsPage(Request $request)
    {
            $products = Product::where('status', 'ACTIVE')
                        ->with(['category', 'reviews'])
                        ->inRandomOrder()
                        ->cursorPaginate(20); 


            $bestsellers = Product::where('status', 'ACTIVE')
                                ->with('category')
                                ->with('reviews')
                                ->whereHas('reviews', function ($query) {
                                    // Ensure there are more than 5 reviews associated with the product
                                    $query->havingRaw('COUNT(*) > 5');
                                })
                                ->inRandomOrder() 
                                ->take(5) 
                                ->get();
            $features = Product::where('status', 'ACTIVE')
                                ->where('is_featured', 'YES')
                                ->with('category')
                                ->with('reviews')
                                ->inRandomOrder() 
                                ->take(5) 
                                ->get();

            $hots = Product::where('status', 'ACTIVE')
                                ->with('category')
                                ->with('reviews')
                                ->inRandomOrder() 
                                ->take(5) 
                                ->get();


              $categories = Category::get();


        $query = $request->input('query');

    // Search in product_name, product_price, and other relevant fields
    $products = Product::where('product_name', 'like', '%' . $query . '%')
        ->orWhere('product_price', 'like', '%' . $query . '%')
        ->orderBy('created_at', 'desc')
        ->paginate(20); 

    return view('products-page', compact('products', 'categories', 'query'));
    }

}
 