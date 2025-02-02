<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Support;
use App\Models\Store;
use App\Models\User;
use Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Str;


class SupportController extends Controller
{
    public function index(Request $request)
    {
        $supports = Support::where('user_id', Auth::id())
                    ->orderBy('created_at', 'desc')
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
            'image' => 'nullable|string|image|max:5000'
        ]);

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

                // Save the image URL and ID to the ticket model
                $ticket->image_url = $imageUrl;
                $ticket->image_id = $imageID;
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


}
