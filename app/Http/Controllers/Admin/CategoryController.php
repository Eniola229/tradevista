<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('created_at', 'desc')->get();

        return view('admin.categories', compact("categories"));
    }

    public function add()
    {

        if (Auth::guard('admin')->check() && Auth::guard('admin')->user()->role === "SUPER" || Auth::guard('admin')->user()->role === "ADMIN") {
          return view('admin.add-category');
        }


       return back()->with('error', 'Oops! You do not have access');
    }

    public function addCategory(Request $request): RedirectResponse
    {
        $request->validate([
            'id' => 'nullable',
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $data = $request->all();

        // Use the 'id' only if it exists, otherwise, create a new record
        $category = Category::updateOrCreate(
            [
                'id' => $data['id'] ?? null, 
            ],
            [
                'name' => $data['name'],
                'description' => $data['description'],
            ]
        );

        return redirect()->back()->with('message', 'Great! The category record has been successfully updated or created.');
    }

        public function delete($id)
    {
        // Retrieve the admin record by ID
        $category = Category::find($id);

        if (!$category) {
            return back()->with('error', 'Category not found.');
        }
        // Check if any product is associated with this category ID
        $productExists = Product::where('category_id', $id)->exists();

        if ($productExists) {
            return redirect()->back()->withErrors(['error' => 'This category is associated with a product and cannot be deleted at the moment.']);
        }

        // Check if the authenticated user is a SUPER admin
        if (Auth::guard('admin')->check() && Auth::guard('admin')->user()->role === "SUPER") {
            $category->delete();
            return back()->with('message', 'Category successfully deleted.');
        }

        return back()->with('error', 'Oops! You do not have access.');
    }

     public function edit($id)
    {
        $category = Category::where('id', $id)->first();

        if (Auth::guard('admin')->check() && Auth::guard('admin')->user()->role === "SUPER" || Auth::guard('admin')->user()->role === "ADMIN") {
            return view('admin.edit-category', compact('category'));
        }


        return back()->with('error', 'Oops! You do not have access');
    }
}
