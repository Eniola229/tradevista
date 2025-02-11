<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Setup;
use App\Models\Product;
use App\Models\Payment;
use App\Models\Order;

class UsersController extends Controller
{
   public function index(Request $request)
    {
        $users = User::orderBy('created_at', 'desc')->get();

        // Pass the collection to the view
        return view('admin.users', compact('users'));
    }

    public function view($id)
    {
       $user = User::with('setup')->findOrFail($id);
       $setup = $user->setup;
       $products = Product::where('user_id', $id)->paginate(5);
       $payments = Payment::where('user_id', $id)->paginate(5);
       $orders = Order::where('user_id', $id)->paginate(5);
        return view('admin.view-user', compact('user', 'setup', 'products', 'payments', 'orders'));
    }
}
