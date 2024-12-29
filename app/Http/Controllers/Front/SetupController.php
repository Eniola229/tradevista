<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Models\Setup;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;

class SetupController extends Controller
{
    public function setup(Request $request)
    {
        $userInfo = Auth::user();
        $setup = Setup::where('user_id', $userInfo->id)->first();
        $payments = Payment::where('user_id', $userInfo->id)->get();
        $pendingPayment = Payment::where('user_id', $userInfo->id)
                         ->where('description', 'SELLER ACCOUNT SETUP FEE')->first();

          return view('dashboard', compact('setup', 'payments', 'pendingPayment'));

    }


    public function createSetup(Request $request)
    {
        $validated = $request->validate([
            'account_type' => 'required|in:SELLER,BUYER',
            'company_name' => 'required_if:account_type,SELLER|max:255',
            'company_description' => 'required_if:account_type,SELLER',
            'company_address_1' => 'required_if:account_type,SELLER|max:255',
            'company_address_2' => 'nullable|max:255',
            'company_mobile_1' => 'required_if:account_type,SELLER|max:15',
            'company_mobile_2' => 'nullable|max:15',
            'company_image' => 'required_if:account_type,SELLER|image|mimes:jpeg,png,jpg,gif|max:5048',
        ]);

        $user = Auth::user();

        if ($request->account_type === 'SELLER') {
            // Check if user already has a setup record
            $setup = Setup::where('user_id', $user->id)->first();

            if ($setup) {
                // Update existing setup record
                $setup->update([
                    'account_type' => $request->account_type,
                    'company_name' => $request->company_name,
                    'company_description' => $request->company_description,
                    'company_address_1' => $request->company_address_1,
                    'company_address_2' => $request->company_address_2,
                    'company_mobile_1' => $request->company_mobile_1,
                    'company_mobile_2' => $request->company_mobile_2,
                ]);
            } else {
                // Upload image to Cloudinary if new setup
                $uploadResult = Cloudinary::upload($request->file('company_image')->getRealPath(), [
                    'folder' => 'seller_images',
                ]);

                $companyImageUrl = $uploadResult->getSecurePath();
                $companyImageId = $uploadResult->getPublicId();

                // Save new seller setup record
                $setup = Setup::create([
                    'user_id' => $user->id,
                    'account_type' => $request->account_type,
                    'company_name' => $request->company_name,
                    'company_description' => $request->company_description,
                    'company_address_1' => $request->company_address_1,
                    'company_address_2' => $request->company_address_2,
                    'company_mobile_1' => $request->company_mobile_1,
                    'company_mobile_2' => $request->company_mobile_2,
                    'company_image' => $companyImageUrl,
                    'company_image_id' => $companyImageId,
                ]);
            }

            // Check if a pending payment exists with description 'SELLER ACCOUNT SETUP FEE'
            $payment = Payment::where('user_id', $user->id)
                              ->where('description', 'SELLER ACCOUNT SETUP FEE')
                              ->where('status', 'PENDING')
                              ->first();

            if ($payment) {
                // Update existing pending payment
                $payment->update([
                    'amount' => 2875 * 100, 
                    'payment_method' => 'PAYSTACK',
                ]);
            } else {
                // Create a new pending payment record if none exists
                $payment = Payment::create([
                    'user_id' => $user->id,
                    'currency' => 'NGN',
                    'description' => 'SELLER ACCOUNT SETUP FEE',
                    'amount' => 2875 * 100,
                    'payment_method' => 'PAYSTACK',
                    'status' => 'PENDING',
                ]);
            }

            // Initialize Paystack payment
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('PAYSTACK_SECRET_KEY'),
            ])->post('https://api.paystack.co/transaction/initialize', [
                'email' => $user->email,
                'amount' => 2875 * 100,
                'currency' => 'NGN',
                'callback_url' => route('payment.callback'),
            ]);

            $data = $response->json();
            if ($data['status'] ?? false) {
                return redirect($data['data']['authorization_url']);
            }

            $payment->status = 'FAILED';
            $payment->save();
            return redirect()->back()->with('error', 'Payment initialization failed 1.');
        } else {
            // Save Buyer Setup Data
            Setup::create([
                'user_id' => $user->id,
                'account_type' => $request->account_type,
            ]);

            return redirect()->route('dashboard')->with('message', 'Great! Buyer setup completed. Start shopping...');
        }
    }

    public function paymentCallback(Request $request)
    {
        $reference = $request->input('reference');
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('PAYSTACK_SECRET_KEY'),
        ])->get("https://api.paystack.co/transaction/verify/{$reference}");

        $data = $response->json();

        dd($data);

        if ($data['status'] ?? false && $data['data']['status'] === 'success') {
            $payment = Payment::where('reference', $reference)->first();
            if ($payment) {
                $payment->status = 'PAID';
                $payment->currency = $data['data']['currency'];
                $payment->save();

                return redirect()->route('dashboard')->with('message', 'Payment successful! Seller setup completed.');
            }
        }

        return redirect()->route('dashboard')->with('error', 'Payment verification failed 2.');
    }
}
