<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('created_at', 'desc')->get();

        if (Auth::guard('admins')->check()) {
            return view('admin.categories', compact("categories"));
        }

        return redirect("admin/login")->withSuccess('Oops! You do not have access');
    }

    public function add()
    {

        if (Auth::guard('admins')->check() && Auth::guard('admins')->user()->role === "SUPER" || Auth::guard('admins')->user()->role === "ADMIN") {
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

        // Check if the authenticated user is a SUPER admin
        if (Auth::guard('admins')->check() && Auth::guard('admins')->user()->role === "SUPER") {
            $category->delete();
            return back()->with('message', 'Category successfully deleted.');
        }

        return back()->with('error', 'Oops! You do not have access.');
    }

     public function edit($id)
    {
        $category = Category::where('id', $id)->first();

        if (Auth::guard('admins')->check() && Auth::guard('admins')->user()->role === "SUPER" || Auth::guard('admins')->user()->role === "ADMIN") {
            return view('admin.edit-category', compact('category'));
        }


        return back()->with('error', 'Oops! You do not have access');
    }
}
