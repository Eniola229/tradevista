<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Support;
use App\Models\Store;
use App\Models\User;
use App\Models\SupportMessage;
use Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Str;


class SupportController extends Controller
{
    public function index(Request $request)
    {
        $supports = Support::where('user_id', auth()->id())
                           ->with('messages') 
                           ->with('attendant') 
                           ->latest()
                           ->get();

        return view('support', compact('supports'));
    }

    /**
     * Store a newly created support ticket.
     */
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'problem_type' => 'required|string',
            'image' => 'nullable|image|max:5000'
        ]);

        $imageUrl = null;
        $imageID = null;

        // Handle Image Upload
        if ($request->hasFile('image')) {
            $uploadCloudinary = cloudinary()->upload(
                $request->file('image')->getRealPath(),
                [
                    'folder' => 'tradevista/user/support/image',
                    'resource_type' => 'auto',
                ]
            );

            // Get the URL and Public ID (Image ID)
            $imageUrl = $uploadCloudinary->getSecurePath(); // This is the image URL
            $imageID = $uploadCloudinary->getPublicId();   // This is the image ID
        }

        $uuid = Str::uuid()->toString();
        $shortUuid = substr($uuid, 0, 6);

        $ticket = Support::create([
            'user_id' => Auth::id(),
            'attendant_id' => null,
            'ticket_id' => "#" . $shortUuid,
            'status' => 'PENDING',
            'problem_type' => $request->problem_type,
            'message' => $request->message,
            'answer' => null,
            'image_url' => $imageUrl,
            'image_id' => $imageID
        ]);

        return response()->json(['message' => 'Support ticket created successfully', 'ticket' => $ticket]);
    }

    /**
     * Display the specified ticket.
     */
    public function show($id)
    {
        $ticket = Support::where('user_id', Auth::id())->findOrFail($id);
        return response()->json($ticket);
    }

    /**
     * Update the status or response of a support ticket (for attendants).
     */
    public function update(Request $request, $id)
    {
        $ticket = Support::findOrFail($id);

        $request->validate([
            'status' => 'required|in:REJECTED,PENDING,APPROVED,ISSUE FIXED',
            'answer' => 'nullable|string',
        ]);

        $ticket->update([
            'status' => $request->status,
            'answer' => $request->answer,
            'attendant_id' => Auth::id(),
        ]);

        return response()->json(['message' => 'Support ticket updated successfully', 'ticket' => $ticket]);
    }

    /**
     * Delete a support ticket (if necessary).
     */
    public function destroy($id)
    {
        $ticket = Support::where('user_id', Auth::id())->findOrFail($id);
        $ticket->delete();

        return response()->json(['message' => 'Support ticket deleted successfully']);
    }

    public function sendMessage(Request $request, $ticketId)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $support = Support::findOrFail($ticketId);

        SupportMessage::create([
            'support_id' => $support->id,
            'sender_id' => Auth::id(),
            'sender_type' => 'user',
            'message' => $validated['message'],
        ]);

        // Optionally update status
        if ($support->status === 'ISSUE FIXED') {
            $support->status = 'PENDING'; // Reopen if user replies
            $support->save();
        }

        return back()->with('success', 'Message sent');
    }

    public function sendUserMessage(Request $request, $id)
    {
        $request->validate(['message' => 'required|string|max:1000']);

        $support = Support::findOrFail($id);

        SupportMessage::create([
            'support_id' => $support->id,
            'sender_id' => Auth::id(),
            'sender_type' => 'user',
            'message' => $request->message,
        ]);

        // If the ticket was previously closed, reopen it
        if ($support->status === 'ISSUE FIXED') {
            $support->status = 'PENDING';
            $support->save();
        }

        return back()->with('success', 'Message sent');
    }

    public function fetchMessages($id)
    {
        $support = Support::with(['messages', 'user'])->findOrFail($id);

        return response()->json([
            'initial' => [
                'message' => $support->message,
                'sender' => 'You', // since this is user side
                'time' => $support->created_at->diffForHumans(),
            ],
            'messages' => $support->messages->map(function ($msg) {
                return [
                    'message' => $msg->message,
                    'sender_type' => $msg->sender_type, // <-- don't convert to label here
                    'created_at' => $msg->created_at->diffForHumans(),
                ];
            }),
        ]);
    }


    public function reply(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $support = Support::findOrFail($id);

        SupportMessage::create([
            'sender_id' => Auth::id(),
            'support_id' => $support->id,
            'sender_type' => 'user',
            'message' => $request->message,
        ]);

        return response()->json(['success' => true]);
    }


}
