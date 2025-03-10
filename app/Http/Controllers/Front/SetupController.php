<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Models\Setup;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Order;
use App\Models\Notification;
use Illuminate\Support\Facades\Http;

class SetupController extends Controller
{
    public function setup(Request $request)
    {
        $userInfo = Auth::user();
        $setup = Setup::where('user_id', $userInfo->id)->first();
        $payments = Payment::where('user_id', $userInfo->id)
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);

        $pendingPayment = Payment::where('user_id', $userInfo->id)
                         ->where('description', 'SELLER ACCOUNT SETUP FEE')
                         ->where('status', 'PENDING')
                         ->first();
        $pendingOrder = Order::where('user_id', $userInfo->id)
                         ->where('delivery_status', 'Processing')
                         ->count();

        $DeliveredOrder = Order::where('user_id', $userInfo->id)
                         ->where('delivery_status', 'Delivered')
                         ->count();

        $productCount = Product::where('user_id', $userInfo->id)->count();

        $notifications = Notification::all();

        return view('dashboard', compact('setup', 'DeliveredOrder', 'pendingOrder', 'payments', 'pendingPayment', 'productCount', 'notifications'));

    }


    public function createSetup(Request $request)
    {
        $validated = $request->validate([
            'account_type' => 'required|in:SELLER,BUYER',
            'company_name' => 'required_if:account_type,SELLER|max:255',
            'company_description' => 'required_if:account_type,SELLER',
            'state' => 'required_if:account_type,SELLER|max:255',
            'address' => 'nullable|max:255',
            'zipcode' => 'nullable|max:255',
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
                    'state' => $request->state,
                    'address' => $request->address,
                    'zipcode' => $request->zipcode,
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
                    'state' => $request->state,
                    'address' => $request->address,
                    'zipcode' => $request->zipcode,
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
                    'amount' => 2875, 
                    'payment_method' => 'PAYSTACK',
                ]);
            } else {
                // Create a new pending payment record if none exists
                $payment = Payment::create([
                    'user_id' => $user->id,
                    'currency' => 'NGN',
                    'description' => 'SELLER ACCOUNT SETUP FEE',
                    'amount' => 2875,
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
        $userID = Auth::id();

        if ($data['status'] ?? false && $data['data']['status'] === 'success') {
            $payment = Payment::where('user_id', $userID)
                ->where('description', 'SELLER ACCOUNT SETUP FEE')
                ->where('status', 'PENDING')
                ->first();

            if ($payment) {
                $payment->update([
                    'status' => 'PAID',
                    'currency' => $data['data']['currency'],
                ]);

                // Retrieve stored update data
                $updateData = session('update_setup_data');
                $companyImagePath = session('company_image_path');

                if ($updateData) {
                    $setup = Setup::where('user_id', $userID)->first();
                    if ($setup) {
                        $setup->fill($updateData);

                        // Handle company image upload
                        if ($companyImagePath && file_exists(storage_path("app/{$companyImagePath}"))) {
                            try {
                                if ($setup->company_image_id) {
                                    Cloudinary::destroy($setup->company_image_id);
                                }

                                $uploadedImage = Cloudinary::upload(storage_path("app/{$companyImagePath}"), [
                                    'folder' => 'seller_images',
                                ]);

                                if ($uploadedImage) { // Ensure the upload was successful
                                    $setup->company_image = $uploadedImage->getSecurePath();
                                    $setup->company_image_id = $uploadedImage->getPublicId();

                                    // Delete temporary image
                                    unlink(storage_path("app/{$companyImagePath}"));
                                } else {
                                    return redirect()->route('dashboard')->with('error', 'Image upload failed.');
                                }
                            } catch (\Exception $e) {
                                return redirect()->route('dashboard')->with('error', 'Image upload failed: ' . $e->getMessage());
                            }
                        }


                        $setup->save();
                    }
                }

                // Clear session data
                session()->forget(['update_setup_data', 'company_image_path']);

                return redirect()->route('dashboard')->with('message', 'Payment successful! Seller setup updated.');
            }
        }

        return redirect()->route('dashboard')->with('error', 'Payment verification failed.');
    }


    public function update(Request $request, $id)
    {
        $setup = Setup::findOrFail($id);
        $user = Auth::user();

        // Store previous account type before update
        $previousAccountType = $setup->account_type;
        $previousName = $setup->company_name;

        // Validate input
        $request->validate([
            'account_type' => 'required|in:BUYER,SELLER',
            'company_name' => 'nullable|string|max:255',
            'company_description' => 'nullable|string',
            'state' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'zipcode' => 'nullable|string|max:255',
            'company_mobile_1' => 'nullable|string|max:15',
            'company_mobile_2' => 'nullable|string|max:15',
            'company_image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
        ]);

        // If upgrading from BUYER to SELLER and company name was empty, require payment
        if ($previousAccountType == "BUYER" && $request->account_type == "SELLER" && empty($previousName)) {
            
            // Check if a pending payment exists
            $payment = Payment::where('user_id', $user->id)
                ->where('description', 'SELLER ACCOUNT SETUP FEE')
                ->where('status', 'PENDING')
                ->first();

            if (!$payment) {
                // Create a new payment record
                $payment = Payment::create([
                    'user_id' => $user->id,
                    'currency' => 'NGN',
                    'description' => 'SELLER ACCOUNT SETUP FEE',
                    'amount' => 2875,
                    'payment_method' => 'PAYSTACK',
                    'status' => 'PENDING',
                ]);
            }

            // Store update request details in the session
            session(['update_setup_data' => $request->except('company_image')]);

            // Store the image in session if uploaded
            if ($request->hasFile('company_image')) {
                $imagePath = $request->file('company_image')->store('temp_images'); // Temporarily store
                session(['company_image_path' => $imagePath]);
            }

            // Redirect to Paystack only if no previous successful payment exists
            if ($payment->status !== 'PAID') {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . env('PAYSTACK_SECRET_KEY'),
                ])->post('https://api.paystack.co/transaction/initialize', [
                    'email' => $user->email,
                    'amount' => 2875 * 100, // Convert to kobo
                    'currency' => 'NGN',
                    'callback_url' => route('payment.callback'), // Redirect to a callback after payment
                ]);

                $data = $response->json();
                if ($data['status'] ?? false) {
                    return redirect($data['data']['authorization_url']); // Redirect to Paystack
                }

                // If payment fails, mark as failed and stop the update
                $payment->update(['status' => 'FAILED']);
                return redirect()->back()->with('error', 'Payment initialization failed.');
            }
        }

        // **Proceed to update the fields**
        $setup->fill($request->except('company_image'));

        // Handle company image upload if provided
        if ($request->hasFile('company_image')) {
            try {
                if ($setup->company_image_id) {
                    Cloudinary::destroy($setup->company_image_id);
                }

                $image = $request->file('company_image');
                $uploadedImage = Cloudinary::upload($image->getRealPath(), [
                    'folder' => 'seller_images',
                ]);

                $setup->company_image = $uploadedImage->getSecurePath();
                $setup->company_image_id = $uploadedImage->getPublicId();
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Image upload failed: ' . $e->getMessage());
            }
        }

        $setup->save(); // Save the updated setup

        return redirect()->back()->with('message', 'Setup updated successfully!');
    }


    public function handlePaymentCallback(Request $request)
    {
        $paymentReference = $request->query('reference');

        // Verify transaction with Paystack
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('PAYSTACK_SECRET_KEY'),
        ])->get("https://api.paystack.co/transaction/verify/{$paymentReference}");

        $data = $response->json();

        if ($data['status'] ?? false && $data['data']['status'] === 'success') {
            $payment = Payment::where('reference', $paymentReference)->first();

            if ($payment) {
                $payment->update(['status' => 'SUCCESS']);

                // Update user setup to SELLER after successful payment
                $setup = Setup::where('user_id', $payment->user_id)->first();
                if ($setup) {
                    $setup->account_type = 'SELLER';
                    $setup->save();
                }

                return redirect()->route('dashboard')->with('message', 'Payment successful! Your account has been upgraded to SELLER.');
            }
        }

        return redirect()->route('dashboard')->with('error', 'Payment verification failed.');
    }


}
