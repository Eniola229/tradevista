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


class ProductController extends Controller
{
    public function products(Request $request)
    {
        $userInfo = Auth::user();
        $products = Product::where('user_id', $userInfo->id)
                    ->orderBy('created_at', 'desc')
                    ->get();

          return view('products', compact('products'));

    }

    //     public function addProducts(Request $request)
    // {
    //     $userInfo = Auth::user();
    //     $setup = Setup::where('user_id', $userInfo->id)
    //                 ->first();

    //       return view('add-product', compact('setup'));

    // }

    public function addEditProduct(Request $request, $id = null)
    {
        if ($id == "") {
            // Add Product
            $title = "Add Product";
            $product = new Product;
            $productdata = null; // No existing product for adding
            $message = "Product added successfully";
        } else {
            // Edit Product
            $title = "Edit Product";
            $productdata = Product::where('id', $id)->first(); // Fetch product by ID
            if (!$productdata) {
                // Handle if the product is not found
                return redirect()->route('products.index')->with('error', 'Product not found.');
            }
            $product = $productdata;
            $message = "Product updated successfully";
        }

        if ($request->isMethod('post')) {
            $data = $request->all();

            // Validation
            $validator = Validator::make($data, [
                'product_name' => [
                    'required',
                    'string',
                    Rule::unique('products', 'product_name')->ignore($product->id ?? null),
                ],
                'product_price' => 'required|string',
                'stock' => 'required',
                'product_weight' => 'required'
            ], [
                'product_name.unique' => 'This product name already exists',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $price = str_replace(',', '', $request->product_price);
            $priceDis = str_replace(',', '', $request->product_discount);


            // Assign data to the product
            $product->product_name = $request->product_name;
            $product->product_price = $price;
            $product->stock = $request->stock;
            $product->product_weight = $request->product_weight;
            $product->product_discount = $priceDis;
            $product->product_description = $request->product_description;
            $product->meta_title = $request->meta_title;
            $product->meta_keywords = $request->meta_keywords;
            $product->meta_description = $request->meta_description;
            $product->status = $request->status;
            $product->category_id = $request->category_id;
            $product->shipping_fee = $request->shipping_fee;

            $product->user_id = Auth::user()->id;

            // Handle Image Upload
            if ($request->hasFile('main_image')) {
                $uploadCloudinary = cloudinary()->upload(
                    $request->file('main_image')->getRealPath(),
                    [
                        'folder' => 'tradevista/user/products/main_image',
                        'resource_type' => 'auto',
                    ]
                );

                // Get the URL and Public ID (Image ID)
                $imageUrl = $uploadCloudinary->getSecurePath(); // This is the image URL
                $imageID = $uploadCloudinary->getPublicId();   // This is the image ID

                // Save the image URL and ID to the product model
                $product->image_url = $imageUrl;
                $product->image_id = $imageID;
            }

            if ($request->hasFile('product_video')) {
                // Check video duration (make sure it doesn't exceed 1 minute)
                $video = $request->file('product_video');
                $path = $video->getRealPath();

                // Using FFmpeg to get the video duration
                $ffmpeg = \FFMpeg\FFMpeg::create();
                $videoStream = $ffmpeg->open($path);
                $duration = $videoStream->getFormat()->get('duration'); // in seconds

                if ($duration > 60) {
                    return redirect()->back()->with('error', 'The video must not exceed 1 minute.');
                }

                // Upload to Cloudinary if duration is valid
                $uploadVideoCloudinary = cloudinary()->upload(
                    $video->getRealPath(),
                    [
                        'folder' => 'tradevista/user/products/videos',
                        'resource_type' => 'video'
                    ]
                );
                $videoUrl = $uploadVideoCloudinary->getSecurePath();
                $videoID = $uploadCloudinary->getPublicId();
                $product->video_url = $videoUrl;
                $product->video_id = $videoID;
            }

            // Save the product
            $product->save();

            session::flash('message', $message);
            return redirect('user/products');
        }

        $categories = Category::all();
        // Returning view with necessary data
        return view('add-product', compact('productdata', 'title', 'categories'));
    }

    public function deleteProduct($id){
        $productImage = Product::select('image_url', 'image_id')->where('id', $id)->first();
        cloudinary()->uploadApi()->destroy($productImage->image_id);
        Product::where('id', $id)->delete();
        $message = 'Product been deleted successfully';
        session::flash('success_message', $message);
        return redirect('user/products');
    }


        public function addAttributes(Request $request, $id)
         {
            if ($request->isMethod('post')) {
                $data = $request->all();

                // Retrieve product ID from form input
                $productId = $data['product_id'];
                $product = Product::find($productId);

                if (!$product) {
                    session::flash('error', 'Product not found.');
                    return redirect()->back();
                }

                foreach ($data['sku'] as $key => $value) {
                    if (!empty($value)) {
                        // Find existing attribute or create a new one
                        $attribute = ProductsAttribute::updateOrCreate(
                            [
                                'product_id' => $productId,
                                'size' => $data['size'][$key], // Match by product_id and size
                            ],
                            [
                                'sku' => $value,
                                'price' => $data['price'][$key] ?? $product->product_price, // Default to product price
                                'stock' => $data['stock'][$key] ?? $product->stock, // Default to product stock
                                'status' => $data['status'][$key] ?? "ACTIVE", // Default to ACTIVE
                            ]
                        );
                    }
                }

                session::flash('message', 'Product Attributes have been saved successfully.');
                return redirect()->back();
            }

            session::flash('error', 'Invalid request method.');
            return redirect()->back();
        }


    public function editAttributes(Request $request, $id){
        if($request->isMethod('post')){
            $data= $request->all();
            // echo "<pre>"; print_r($data); die;
            foreach($data['attrId'] as $key => $attr){
                if(!empty($attr)){
                    ProductsAttribute::where(['id'=>$data['attrId'][$key]])->update(['price'=> $data['price'][$key], 'stock'=>$data['stock'][$key]]);
                }
            }
            $message ="Product Attributes has been updated successfully";
            session::flash('success_message', $message);
            return redirect()->back();
        }
    }



    public function addImages(Request $request, $id){
        if($request->isMethod('post')){
            $data= $request->all();
            if($request->hasFile('images')){
                $productImages = $request->file('images');

                if ($productImages) {
                    foreach ($productImages as $productImage) {
                        $uploadProductImage = cloudinary()->upload($productImage->getRealPath(), [
                            'folder' => 'tradevista/products/products_images',
                            'transformation' => [
                                'quality' => 'auto',
                                'fetch_format' => 'auto'
                            ]
                        ]);
                        $imageUrl = $uploadProductImage->getSecurePath();
                        $imageId = $uploadProductImage->getPublicId();

                        $productId = Product::where('id', $id)->first();
                        $productImg = new ProductsImage();
                        $productImg->product_id = $productId->id;
                        $productImg->image_url = $uploadProductImage->getSecurePath();
                        $productImg->image_id = $uploadProductImage->getPublicId();
                        $productImg->save();
                    }
                }
                $message ="Product Images has been added successfully";
                session::flash('message', $message);
                return redirect()->back();
            }
        }
        $productdata = Product::with('images')->select('id', 'id', 'product_name', 'product_code', 'product_color', 'image_url')->where('id', $id)->first();
        // dd($productdata); die;
        // $productdata = json_decode(json_encode($productdata), true);
        // $categorydata = Category::where('id', $id)->first();

        $title= "Product Images";
        return view('view-product')->with(compact('title', 'productdata'));
    }

  


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
                    ->with('category')
                    ->with('reviews')
                    ->inRandomOrder()        
                    ->get();

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

    return view('products-page', compact('products', 'categories'));
    }

}
 