<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Withdraw;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class WithdrawController extends Controller
{
    public function index()
    {
        $withdrawals = Withdraw::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        $balance = User::where('id', Auth::id())->first();
        return view('withdraw', compact('balance', 'withdrawals'));
    }

    public function withdraw(Request $request)
    {
        $userBalance = User::where('id', Auth::id())->first();

        if (!$userBalance || $userBalance->balance < 1000) {
            return response()->json(['status' => 'error', 'message' => 'Insufficient balance!'], 400);
        }

        $amount = $request->input('amount');

        if ($amount > $userBalance->balance) {
            return response()->json(['status' => 'error', 'message' => 'Withdrawal amount exceeds balance!'], 400);
        }

        // Deduct balance
        $userBalance->balance -= $amount;
        $userBalance->save();

        // Store withdrawal request
        Withdraw::create([
            'user_id' => Auth::id(),
            'amount' => $amount,
            'status' => 'PENDING'
        ]);

        return response()->json(['status' => 'success', 'message' => 'Withdrawal requested successfully!']);
    }

    public function uploadReceipt(Request $request, $id)
    {
        $withdrawal = Withdraw::findOrFail($id);

        if ($request->hasFile('receipt')) {
            $file = $request->file('receipt');
            $path = $file->store('receipts', 'public');
            $withdrawal->receipt = $path;
            $withdrawal->status = 'ACCEPTED';
            $withdrawal->save();
        }

        return back()->with('success', 'Receipt uploaded successfully!');
    }
}
