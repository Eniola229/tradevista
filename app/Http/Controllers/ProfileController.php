<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Setup;
use App\Models\ShippingAddress;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;


class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = Auth::user()->id;
        $setup = Setup::where('user_id', $user)->first();
        $shippingAddress = ShippingAddress::where('user_id', $user)->first();

        return view('profile', compact('setup', 'shippingAddress'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
            $user = auth()->user();

            // Validate the input
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . $user->id,
                'phone_number' => 'required|string|max:15',
                'password' => 'nullable|string|min:8',
            ]);

            // Update user details
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->phone_number = $request->input('phone_number');

            // Update password if provided
            if ($request->filled('password')) {
                $user->password = Hash::make($request->input('password'));
            }

            $user->save();

            return redirect()->back()->with('message', 'Profile updated successfully.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Store a new address record.
     */
    public function storeaddress(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'user_id' => 'required|uuid',
            'country' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'town_city' => 'required|string|max:255',
            'zip' => 'required|string|max:10',
            'address_type' => 'required|string|in:home,work,other',
        ]);

        // Create the address record
        $address = ShippingAddress::create([
            'user_id' => $request->user_id,
            'country' => $request->country,
            'state' => $request->state,
            'town_city' => $request->town_city,
            'zip' => $request->zip,
            'address_type' => $request->address_type,
        ]);

        return redirect()->back()->with('message', 'Address created successfully');
    }

    /**
     * Update an existing address record.
     */
    public function updateaddress(Request $request, $id)
    {
        // Validate the incoming request
        $request->validate([
            'user_id' => 'required|uuid',
            'country' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'town_city' => 'required|string|max:255',
            'zip' => 'required|string|max:10',
            'address_type' => 'required|string|in:home,work,other',
        ]);

        // Find the address record by ID
        $address = ShippingAddress::findOrFail($id);

        // Update the address record
        $address->update([
            'user_id' => $request->user_id,
            'country' => $request->country,
            'state' => $request->state,
            'town_city' => $request->town_city,
            'zip' => $request->zip,
            'address_type' => $request->address_type,
        ]);

        return redirect()->back()->with('message', 'Address updated successfully');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate file type and size
        ]);

        $user = auth()->user();

        // Upload to Cloudinary
        $uploadedFileUrl = Cloudinary::upload($request->file('avatar')->getRealPath(), [
            'folder' => 'avatars',
        ])->getSecurePath();

        // Update the user's avatar
        $user->avatar = $uploadedFileUrl;
        $user->save();

        return redirect()->back()->with('message', 'Avatar updated successfully!');
    }
}
