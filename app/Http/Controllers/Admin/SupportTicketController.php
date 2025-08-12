<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Support;
use App\Models\Store;
use App\Models\SupportMessage;
use App\Models\User;
use Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Str;

class SupportTicketController extends Controller
{
    public function index(Request $request)
    {
        $supports = Support::with(['user', 'messages']) 
                         ->orderBy('created_at', 'desc')
                         ->paginate(10); 

        return view('admin.support', compact('supports'));
    }
    
    public function fetchMessages($id)
    {
        $support = Support::with(['messages'])->findOrFail($id);

        return response()->json([
            'initial' => [
                'message' => $support->message,
                'sender' => $support->user->name,
                'time' => $support->created_at->diffForHumans(),
            ],
            'messages' => $support->messages->map(function ($msg) {
                return [
                    'message' => $msg->message,
                    'sender_type' => $msg->sender_type,
                    'created_at' => $msg->created_at->diffForHumans(),
                ];
            }),
        ]);
    }

    public function sendMessage(Request $request, $id)
    {
        $request->validate(['message' => 'required|string']);

        $support = Support::findOrFail($id);

        $msg = new SupportMessage();
        $msg->support_id = $support->id;
        $msg->message = $request->message;
        $msg->sender_type = 'admin';
        $msg->sender_id = Auth::id();
        $msg->save();

        $support->attendant_id = Auth::id();
        $support->update();

        return response()->json(['success' => true]);
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

    public function closeTicket($id)
    {
        $support = Support::findOrFail($id);
        $support->status = 'ISSUE FIXED';
        $support->save();

        return back()->with('success', 'Ticket closed');
    }

}
