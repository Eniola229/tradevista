<?php

namespace App\Http\Controllers\Admin;

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


class AdminRefererController extends Controller
{
    public function index(Request $request)
    {
        $referers = Referer::with(['referer', 'user'])->get();

        // Pass the collection to the view
        return view('admin.referer', compact('referers'));
    }

}
