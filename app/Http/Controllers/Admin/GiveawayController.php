<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Giveaway;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class GiveawayController extends Controller
{
    public function index()
    {
        return view('admin.giveaways', [
            'giveaways' => Giveaway::all()
        ]);
    }

    public function create()
    {
        return view('admin.giveaways-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'image' => 'required|image',
            'price' => 'required|numeric'
        ]);

            if ($request->hasFile('image')) {
                $uploadCloudinary = cloudinary()->upload(
                    $request->file('image')->getRealPath(),
                    [
                        'folder' => 'tradevista/giveaways/products/image',
                        'resource_type' => 'auto',
                    ]
                );

                // Get the URL and Public ID (Image ID)
                $imageUrl = $uploadCloudinary->getSecurePath(); 
                $imageID = $uploadCloudinary->getPublicId();   
            }

        Giveaway::create([
            'title' => $request->title,
            'image' => $imageUrl,
            'price' => $request->price
        ]);

        return redirect()->back()->with('success', 'Giveaway item added.');
    }
}
