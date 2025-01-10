<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;

class TeamController extends Controller
{
    public function team()
    {
        $admins = Admin::orderBy('created_at', 'desc')->get();

        if (Auth::guard('admin')->check()) {
            return view('admin.team', compact("admins"));
        }

        return redirect("admin/login")->withSuccess('Oops! You do not have access');
    }

    public function add()
    {

        if (Auth::guard('admin')->check() && Auth::guard('admin')->user()->role === "SUPER") {
            return view('admin.add-admin');
        }

        return back()->with('error', 'Oops! You do not have access');
    }

     public function edit($id)
    {
        $admin = Admin::where('id', $id)->first();

        if (Auth::guard('admin')->check() && Auth::guard('admin')->user()->role === "SUPER") {
            return view('admin.edit-admin', compact('admin'));
        }


        return back()->with('error', 'Oops! You do not have access');
    }

    public function delete($id)
    {
        // Retrieve the admin record by ID
        $admin = Admin::find($id);

        if (!$admin) {
            return back()->with('error', 'Admin not found.');
        }

        // Check if the authenticated user is a SUPER admin
        if (Auth::guard('admin')->check() && Auth::guard('admin')->user()->role === "SUPER") {
            $admin->delete();
            return back()->with('message', 'Admin successfully deleted.');
        }

        return back()->with('error', 'Oops! You do not have access.');
    }


}
