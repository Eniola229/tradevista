<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Withdraw;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class WIthdraweralController extends Controller
{
    public function index()
    {
        // Paginate results with 10 withdrawals per page
        $withdrawals = Withdraw::with('user')->orderBy('created_at', 'desc')->paginate(10);

        // Pass the paginated results to the view
        return view('admin.withdraw', compact('withdrawals'));
    }

    public function uploadReceipt(Request $request)
    {
        $request->validate([
            'withdrawal_id' => 'required|exists:withdrawals,id',
            'receipt' => 'required|image|max:2048',
        ]);

        $withdrawal = Withdraw::findOrFail($request->withdrawal_id);

        // Upload receipt to Cloudinary in the specified folder
        $uploadedFile = Cloudinary::upload($request->file('receipt')->getRealPath(), [
            'folder' => 'tradevista/user/withdraw_receipts',  // Folder name
        ])->getSecurePath();


        // Save receipt URL in database
        $withdrawal->receipt = $uploadedFile;
        $withdrawal->status = 'ACCEPTED';
        $withdrawal->save();

        return response()->json(['message' => 'Receipt uploaded successfully']);
    }
}
