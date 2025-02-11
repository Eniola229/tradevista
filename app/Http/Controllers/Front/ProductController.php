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
            $message = "Product added successfully. The Sales Team will review and approve your post within an hour. If it is not approved, please feel free to contact us.";
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
            $product->category_id = $request->category_id;
            $product->shipping_fee = $request->shipping_fee;

            if ($id == "") {
                $product->status = "INACTIVE";
            }

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
                // Upload to Cloudinary
                $uploadVideoCloudinary = cloudinary()->upload(
                    $request->file('product_video')->getRealPath(),
                    [
                        'folder' => 'tradevista/user/products/videos',
                        'resource_type' => 'video'
                    ]
                );

                // Get video details
                $videoUrl = $uploadVideoCloudinary->getSecurePath();
                $videoID = $uploadVideoCloudinary->getPublicId();
                $videoDuration = $uploadVideoCloudinary->getDuration(); // Duration in seconds

                // Check if the video exceeds 60 seconds
                if ($videoDuration > 60) {
                    return redirect()->back()->with('error', 'The video must not exceed 1 minute.');
                }

                // Save video details
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


        $query = $request->input('query');

        // Search in product_name, product_price, and other relevant fields
        $products = Product::where('product_name', 'like', '%' . $query . '%')
            ->orWhere('product_price', 'like', '%' . $query . '%')
            ->orderBy('created_at', 'desc')
            ->get();

    return view('products-page', compact('products', 'categories', 'products', 'query'));
    }

}
 