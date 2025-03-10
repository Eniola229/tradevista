<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = Notification::orderBy('created_at', 'desc')->get();
        return view('admin.notifications', compact('notifications'));
    }

    public function add(Request $request)
    {
        return view('admin.add-notifications');
    }

    public function addNotification(Request $request): RedirectResponse
    {
        $request->validate([
            'id' => 'nullable',
            'expiry_date' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'links' => 'nullable|url|string|max:255',
        ]);

        $data = $request->all();

        // Use the 'id' only if it exists, otherwise, create a new record
        $category = Notification::updateOrCreate(
            [
                'id' => $data['id'] ?? null, 
            ],
            [
                'expiry_date' => $data['expiry_date'],
                'description' => $data['description'],
                'links' => $data['links'],
            ]
        );

        return redirect()->back()->with('message', 'Great! The notification have been added and sent to all customer successfully.');
    }


        public function delete($id)
    {
        // Retrieve the admin record by ID
        $notification = Notification::find($id);

        if (!$notification) {
            return back()->with('error', 'Category not found.');
        }

        // Check if the authenticated user is a SUPER admin
        if (Auth::guard('admin')->check() && Auth::guard('admin')->user()->role === "SUPER") {
            $notification->delete();
            return back()->with('message', 'Notification successfully deleted.');
        }

        return back()->with('error', 'Oops! You do not have access.');
    }


}
