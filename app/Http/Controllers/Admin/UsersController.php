<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
   public function index(Request $request)
    {
        $users = User::orderBy('created_at', 'desc')->get();

        // Pass the collection to the view
        return view('admin.users', compact('users'));
    }
}
