<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Withdraw;
use App\Models\User;
use App\Models\Order;
use App\Models\Setup;
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
        $user = Auth::user();
        $userBalance = User::where('id', $user->id)->first();
        $userSetup = Setup::where('user_id', $user->id)->first();

        if (!$userSetup || !$userSetup->account_type) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please check your dashboard and set up your account.'
            ], 400);
        }

        if (!$userBalance || $userBalance->balance < 1000) {
            return response()->json([
                'status' => 'error',
                'message' => 'Insufficient balance!'
            ], 400);
        }

        if ($userSetup->account_type === 'SELLER') {
            $hasSoldProduct = Order::where('user_id', $user->id)->exists();
            $hasBoughtProduct = Order::where('user_id', $user->id)->exists();
            if (!$hasSoldProduct && !$hasBoughtProduct) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'As a seller, you must have either sold a product or bought one before requesting a withdrawal.'
                ], 400);
            }
        } elseif ($userSetup->account_type === 'BUYER') {
            $totalPurchase = Order::where('user_id', $user->id)->sum('total');
            if ($totalPurchase < 1000) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'As a buyer, you must have purchased a product worth more than N1000 before requesting a withdrawal.'
                ], 400);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid account type. Please check your dashboard and update your account setup.'
            ], 400);
        }

        $amount = $request->input('amount');
        $accountNumber = $request->input('account_number');
        $bankName = $request->input('bank_name');
        $accountName = $request->input('account_name');

        if ($amount > $userBalance->balance) {
            return response()->json(['status' => 'error', 'message' => 'Withdrawal amount exceeds balance!'], 400);
        }

        $userBalance->balance -= $amount;
        $userBalance->save();

        $withdraw = Withdraw::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'status' => 'PENDING',
            'account_number' => $accountNumber,
            'bank_name' => $bankName,
            'account_name' => $accountName,
            'receipt' => null, // Initially null until admin uploads
            'receipt_id' => null,
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
