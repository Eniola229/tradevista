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
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

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
            'type' => 'required|in:GENERAL,CUSTOMERS',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->all();

        $imageUrl = null;
        $imageID = null;

        // Upload to Cloudinary if image is present
        if ($request->hasFile('image')) {
            $uploadCloudinary = cloudinary()->upload(
                $request->file('image')->getRealPath(),
                [
                    'folder' => 'notifications',
                    'resource_type' => 'image',
                ]
            );

            $imageUrl = $uploadCloudinary->getSecurePath();
            $imageID = $uploadCloudinary->getPublicId();
        }

        // Create or update the notification
        Notification::updateOrCreate(
            ['id' => $data['id'] ?? null],
            [
                'expiry_date' => $data['expiry_date'],
                'description' => $data['description'],
                'links' => $data['links'],
                'type' => $data['type'],
                'image_url' => $imageUrl,
                'image_id' => $imageID,
            ]
        );

        return redirect()->back()->with('message', 'Great! The notification has been added and sent successfully.');
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
