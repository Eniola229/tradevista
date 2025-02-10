<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\Order;
use App\Models\Cart;
use App\Models\OrderProduct;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
public function getUserOrders()
{
    $user = Auth::user(); // Get the authenticated user
    $orders = Order::where('user_id', $user->id)
                   ->with('orderProducts') // Load related products if needed
                   ->orderBy('created_at', 'desc')
                   ->paginate(10); // Paginate with 10 orders per page

    return view('orders', compact('orders'));
}

    public function viewOrder($id)
    {
        $order = Order::with('orderProducts')->where('id', $id)->firstOrFail();

        return view('order-view', compact('order'));
    }

public function verifyPayment(Request $request)
{
    // Validate required fields from the checkout form
    $request->validate([
        'first_name'  => 'required',
        'last_name'   => 'required',
        'phone'       => 'required',
        'email'       => 'required|email',
        'stateCode'   => 'required',
        'cityName'    => 'required',
        'zip'         => 'required',
        'shipping_address' => 'required',
    ]);

    $reference = $request->reference;
    
    // Verify payment with Paystack API
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . rawurlencode($reference),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer " . config('services.paystack.secret_key'),
            "Cache-Control: no-cache",
        ],
    ]);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
        return response()->json(['success' => false, 'message' => "cURL Error: " . $err]);
    }
    $result = json_decode($response, true);
    if (!$result['status'] || $result['data']['status'] != 'success') {
        return response()->json(['success' => false, 'message' => "Payment verification failed"]);
    }

    // Payment is verified; extract details
    $user = Auth::user();
    $amountPaid = $result['data']['amount'] / 100; // convert from kobo to Naira
    $currency = $result['data']['currency'];
    $transactionId = $result['data']['id'];

    // Record the Payment in the payments table
    Payment::create([
        'user_id'       => $user->id,
        'currency'      => $currency,
        'amount'        => $amountPaid,
        'description'   => 'Payment for order via Paystack, reference: ' . $reference,
        'payment_method'=> 'Paystack',
        'status'        => 'PAID'
    ]);

    // Create the Order record
    $subtotal = floatval($request->subtotal);
    $shippingFee = floatval($request->shipping_fee);
    $totalAmount = $subtotal + $shippingFee;

    $order = Order::create([
        'user_id'          => $user->id,
        'transaction_id'   => $transactionId,
        'payment_status'   => 'PAID',
        'delivery_status'  => 'Processing',
        'shipping_address' => $request->shipping_address,
        'total_weight'     => 0,
        'subtotal'         => $subtotal,
        'total'            => $totalAmount,
    ]);

    // Retrieve cart items from the database
    $cartItems = Cart::where('user_id', $user->id)->get();

    if ($cartItems->isEmpty()) {
        return response()->json(['success' => false, 'message' => 'Your cart is empty.']);
    }

    $sellerAmounts = [];  // To track each seller’s earnings and products
    $adminTotal = 0;

DB::transaction(function () use ($cartItems, $order, &$sellerAmounts, &$adminTotal) {
    foreach ($cartItems as $item) {
        $product = Product::find($item->product_id);
        if (!$product) continue;

        $sellerId = $product->user_id;
        $productPrice = floatval($product->product_price);
        $itemTotal = $productPrice * $item->quantity;
        $sellerShare = round(0.85 * $itemTotal, 2);
        $adminShare = round(0.15 * $itemTotal, 2);
        
        \Log::info("Processing Order: Product {$product->id} - Price: {$productPrice}, Qty: {$item->quantity}, Total: {$itemTotal}, Seller Share: {$sellerShare}");

        // Create OrderProduct record
        OrderProduct::create([
            'order_id'      => $order->id,
            'product_id'    => $product->id,
            'product_price' => $productPrice,
            'product_qty'   => $item->quantity,
            'product_total' => $itemTotal,
        ]);

        // Record Payment
        Payment::create([
            'user_id'       => Auth::id(),
            'currency'      => 'NGN',
            'amount'        => number_format($sellerShare, 2, '.', ''),
            'description'   => 'Payment for new order placed',
            'payment_method'=> 'Paystack',
            'status'        => 'PAID'
        ]);

        // Update seller balance
        User::where('id', $sellerId)->increment('balance', $sellerShare);

        // Track seller earnings
        if (!isset($sellerAmounts[$sellerId])) {
            $sellerAmounts[$sellerId] = ['amount' => 0, 'products' => []];
        }
        $sellerAmounts[$sellerId]['amount'] += $sellerShare;
        $sellerAmounts[$sellerId]['products'][] = $product;

        $adminTotal += $adminShare;
    }
});


    // Clear the cart for this user after order is placed
    Cart::where('user_id', $user->id)->delete();

    // Send Email notifications
    $this->sendOrderEmails($user, $order, $sellerAmounts);

    return response()->json(['success' => true, 'message' => 'Order placed successfully!']);
}

    
    private function sendOrderEmails($buyer, $order, $sellerAmounts)
    {
        // Notify each seller with details about the products sold
        foreach ($sellerAmounts as $sellerId => $data) {
            $seller = User::find($sellerId);
            Mail::send('emails.seller_notification', [
                'order'    => $order,
                'seller'   => $seller,
                'amount'   => $data['amount'],
                'products' => $data['products']
            ], function ($message) use ($seller) {
                $message->to($seller->email)
                        ->subject("New Order Received - " . config('app.name'));
            });
        }
        
        // Notify the admin
        Mail::send('emails.admin_notification', ['order' => $order], function ($message) {
            $message->to("tradevista2015@gmail.com")
                    ->subject("New Order Placed - " . config('app.name'));
        });
        
        // Send order confirmation to the buyer
        Mail::send('emails.buyer_confirmation', ['order' => $order], function ($message) use ($buyer) {
            $message->to($buyer->email)
                    ->subject("Order Confirmation - " . config('app.name'));
        });
    }
}
