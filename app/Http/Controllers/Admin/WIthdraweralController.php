<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Withdraw;
use App\Models\Payment;
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

    public function manageWithdrawal(Request $request)
    {
        $request->validate([
            'withdrawal_id' => 'required|exists:withdrawals,id',
            'status' => 'required|in:ACCEPTED,REJECTED',
            'receipt' => 'nullable|image|max:2048',
            'note' => 'nullable|string',
        ]);

        $withdrawal = Withdraw::findOrFail($request->withdrawal_id);

        // Check if the withdrawal has already been updated
        if ($withdrawal->created_at != $withdrawal->updated_at) {
            return response()->json(['message' => 'This withdrawal request has already been processed and cannot be updated again.'], 403);
        }

        $user = $withdrawal->user;

        if ($request->status === 'ACCEPTED') {
            if ($request->hasFile('receipt')) {
                // Upload receipt to Cloudinary
                $uploadedFileUrl = Cloudinary::upload($request->file('receipt')->getRealPath(), [
                    'folder' => 'tradevista/user/withdraw_receipts',
                ])->getSecurePath();

                $withdrawal->receipt = $uploadedFileUrl;
            }

            $withdrawal->status = 'ACCEPTED';
            $withdrawal->note = $request->note;
            $withdrawal->save();

            // Create a payment record
            Payment::create([
                'user_id' => $user->id,
                'currency' => 'NGN',
                'amount' => $withdrawal->amount,
                'description' => 'Withdrawal approved',
                'payment_method' => 'BANK TRANSFER',
                'status' => 'PAID',
            ]);

            return response()->json(['message' => 'Withdrawal approved and receipt uploaded successfully.']);
        } elseif ($request->status === 'REJECTED') {
            // Refund user's balance
            $user->balance += $withdrawal->amount;
            $user->save();

            $withdrawal->status = 'REJECTED';
            $withdrawal->note = $request->note;
            $withdrawal->save();

            // Create a payment record for the refund
            Payment::create([
                'user_id' => $user->id,
                'currency' => 'NGN',
                'amount' => $withdrawal->amount,
                'description' => 'Withdrawal rejected and amount refunded',
                'payment_method' => 'REFUND',
                'status' => 'PAID',
            ]);

            return response()->json(['message' => 'Withdrawal rejected and amount refunded to customer.']);
        }
    }


}
