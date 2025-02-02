<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Support;
use App\Models\Store;
use App\Models\User;
use Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Str;

class SupportTicketController extends Controller
{
    public function index(Request $request)
    {
        $supports = Support::orderBy('created_at', 'desc')
                        ->paginate(10); 
        return view('admin.support', compact('supports'));
    }
    
    public function answer(Request $request, $id)
    {
        // Validate the answer
        $validatedData = $request->validate([
            'answer' => 'required|string|max:1000',
        ]);

        // Find the support ticket by ID
        $support = Support::findOrFail($id);

        // Update the answer field
        $support->answer = $validatedData['answer'];
        $support->attendant_id = Auth::id();
        $support->status = "ISSUE FIXED";
        $support->save();

        // Return a response indicating success
        return response()->json([
            'status' => 'success',
            'message' => 'Answer submitted successfully.',
            'answer' => $support->answer
        ]);
    }
}
