<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\Setup;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductsAttribute;
use App\Models\ProductsImage;
use App\Models\Review;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\RedirectResponse;


class AdminProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->get();

        return view('admin.products', compact("products"));
    }

     public function view($id)
    {
        $product = Product::where('id', $id)->first();
        $userInfo = User::where('id', $product->user_id)->first();
        $setup = Setup::where('user_id', $userInfo->id)->first();
        $category = Category::where('id', $product->category_id)->first();
        $attribute = ProductsAttribute::where('product_id', $product->id)->first();
        $image = ProductsImage::where('product_id', $product->id)->first();
        $images = ProductsImage::where('product_id', $product->id)->get();
        $reviews = Review::where('product_id', $product->id)
                                ->with('user')
                                ->get();
        $reviewCount = Review::where('product_id', $product->id)->count();


        return view('admin.admin-view-product', compact('product', 'setup', 'category', 'attribute', 'image', 'images', 'reviews', 'reviewCount'));
    }

    public function changeStatus(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $product->status = $request->new_status;
        $product->save();

        return redirect()->back()->with('message', 'Product status has been updated.');
    }

    public function deleteProduct(Request $request)
    {
        $product = Product::find($request->product_id);

        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        $product->delete();

        return redirect()->route('admin.products')->with('success', 'Product deleted successfully.');
    }


}
