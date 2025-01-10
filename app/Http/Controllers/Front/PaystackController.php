<?php

namespace App\Http\Controllers;

use App\Mail\AdminOrderNotificationMail;
use App\Mail\OrderConfirmationMail;
use App\Mail\StorePickConfirmationMail;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use DB;
use Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session as LaravelSession;
use Validator;

class PaystackController extends Controller
{
    public function paystackCheckOut(Request $request)
    {
        $error = Product::checkStockLimit();
        if ($error) {
            return response()->json(['error' => $error], 400);
        }

        $deliveryMethod = $request->input('delivery_method', 'ship');
        $rules = [
            'email' => 'required|email',
            'phone' => 'required|numeric',
        ];

        if ($deliveryMethod !== 'pickup') {
            $rules = array_merge($rules, [
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'phone' => 'required|numeric',
                'address' => 'required|string',
                'postal' => 'required|string',
                'cityName' => 'required|string',
                'stateCode' => 'required|string',
                'countryCode' => 'required|string',
            ]);
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $user = auth()->user();
        $cart = [];
        $subTotal = 0.0;

        $cartItems = Cart::getCartItems($user);

        foreach ($cartItems as $item) {
            $product = $item->product;

            if (!$product) {
                continue;
            }

            $discountedPrice = Product::getDiscountedPrice($product->id);
            $finalPrice = ($discountedPrice > 0) ? $discountedPrice : $product->product_price;

            if ($finalPrice <= 0) {
                continue;
            }

            $itemTotal = $item->quantity * $finalPrice;
            $subTotal += $itemTotal;

            $cart[] = [
                'product_id' => $product->id,
                'product_code' => $product->product_code,
                'name' => $product->product_name,
                'price' => $finalPrice,
                'quantity' => $item->quantity,
                'image_url' => $product->image_url,
            ];
        }

        try {
            $order = Order::create([
                'user_id' => $user->id,
                'delivery_status' => 'PENDING',
                'total_amount' => $subTotal,
                'delivery_method' => $deliveryMethod,
                'shipping_address' => json_encode($request->only(['address', 'postal', 'cityName', 'stateCode', 'countryCode'])),
            ]);

            foreach ($cart as $item) {
                $order->orders_products()->create([
                    'user_id' => $user->id,
                    'product_id' => $item['product_id'],
                    'product_price' => $item['price'],
                    'product_code' => $item['product_code'],
                    'product_qty' => $item['quantity'],
                    'product_total' => $item['quantity'] * $item['price'],
                ]);
            }

            session()->put('order_id', $order->id);

            // Initialize Paystack Payment
            $paystack = new \Yabacon\Paystack(env('PAYSTACK_SECRET_KEY'));
            $tranx = $paystack->transaction->initialize([
                'amount' => $subTotal * 100, // Convert to kobo
                'email' => $request->input('email'),
                'callback_url' => route('paystack.callback'),
                'metadata' => [
                    'order_id' => $order->id,
                    'cart' => $cart,
                ],
            ]);

            return response()->json(['approval_url' => $tranx->data->authorization_url]);
        } catch (\Exception $e) {
            Log::error('Paystack Checkout Error: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json(['error' => 'An error occurred during checkout. Please try again.'], 500);
        }
    }

    public function paystackCallback(Request $request)
    {
        DB::beginTransaction();

        try {
            $paystack = new \Yabacon\Paystack(env('PAYSTACK_SECRET_KEY'));
            $tranx = $paystack->transaction->verify([ 'reference' => $request->query('reference') ]);

            if ('success' === $tranx->data->status) {
                $orderId = $tranx->data->metadata->order_id;
                $order = Order::find($orderId);

                if (!$order) {
                    throw new \Exception('Order not found.');
                }

                $order->transaction_id = $tranx->data->reference;
                $order->payment_status = 'Paid';
                $order->delivery_status = 'PENDING';
                $order->save();

                Cart::clearCart(session()->getId(), auth()->id());

                DB::commit();

                return redirect()->route('checkout.success')->with('success', 'Payment successful and order completed.');
            } else {
                return redirect()->route('checkout.index')->with('error', 'Payment failed.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('checkout.index')->with('error', $e->getMessage());
        }
    }
}
