<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Models\Referer;
use App\Models\Review;
use App\Models\Cart;
use Illuminate\Support\Facades\Http;
use Validator;
use Session;
use Illuminate\Validation\Rule;


class RefererController extends Controller
{
    public function index(Request $request)
    {
        // Retrieve all referers for the authenticated user
        $referers = Referer::where('user_id', Auth::id())
                    ->with('referer')
                    ->get();

        // Pass the collection to the view
        return view('referer', compact('referers'));
    }

}
