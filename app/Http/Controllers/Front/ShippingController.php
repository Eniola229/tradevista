<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Setup;
use App\Models\StatePrice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class ShippingController extends Controller
{

    public function validateAddress(Request $request)
    {
        try {
            //Log::info('Validating address with:', $request->all()); // Log input

            //Log::info('Using token: ' . env('SHIPBUBBLE_API_KEY'));

            
         $response = Http::withToken(env('SHIPBUBBLE_API_KEY'))
            ->post('https://api.shipbubble.com/v1/shipping/address/validate', [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address
            ]);


            Log::info('Shipbubble response:', $response->json()); // Log

            return response()->json($response->json(), $response->status());

            Log::info('Shipbubble response:', $response->json()); // Log success

        } catch (\Exception $e) {
            Log::error('Shipbubble validation error: ' . $e->getMessage());
            return response()->json(['error' => 'Server error. Please try again.'], 500);
        }
    }


    public function calculateShipping(Request $request)
    {
        try {
            $request->validate([
                'receiver.postal' => 'required|string',
            ]);

            $receiverPostal = $request->input('receiver.postal');
            $userId = Auth::id();
            $cartItems = Cart::where('user_id', $userId)->get();

            if ($cartItems->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'Your cart is empty!'], 400);
            }

            $groupedItemsBySeller = [];

            foreach ($cartItems as $cartItem) {
                $product = Product::find($cartItem->product_id);

                if (!$product) {
                    Log::warning("Product not found for cartItem", ['product_id' => $cartItem->product_id]);
                    continue;
                }

                $seller = $product->user;
                if (!$seller) {
                    Log::warning("Product has no associated seller", ['product_id' => $product->id]);
                    continue;
                }

                if (
                    empty($product->product_name) ||
                    empty($product->product_price) ||
                    empty($product->product_weight)
                ) {
                    Log::warning("Product missing required fields", ['product_id' => $product->id, 'product_name' => $product->product_name, 'product price' => $product->product_price, "product_weight" => $product->product_weight]);
                    continue;
                }

                $startup = Setup::where('user_id', $seller->id)->first();
                if (!$startup || empty($startup->zipcode)) {
                    Log::warning("Seller setup missing or zipcode empty", ['seller_id' => $seller->id]);
                    continue;
                }

                $groupedItemsBySeller[$seller->id]['sender_postal'] = $startup->zipcode;

                $groupedItemsBySeller[$seller->id]['items'][] = [
                    'name'        => $product->product_name,
                    'description' => strip_tags($product->product_description ?? 'No description'),
                    'unit_weight' => floatval($product->product_weight),
                    'unit_amount' => floatval($product->product_price),
                    'quantity'    => intval($cartItem->quantity ?? 1),
                    'dimension'   => [
                        'length' => floatval($product->length ?? 10),
                        'width'  => floatval($product->width ?? 10),
                        'height' => floatval($product->height ?? 10),
                    ],
                ];
            }

            if (empty($groupedItemsBySeller)) {
                return response()->json(['success' => false, 'message' => 'No valid sellers found for your cart items.'], 400);
            }

            $shippingDetails = [];

            foreach ($groupedItemsBySeller as $sellerId => $data) {
                $dimension = $this->calculateMaxDimension($data['items']);

                $packageItems = collect($data['items'])->map(function ($item) {
                    return [
                        'name'         => $item['name'],
                        'description'  => $item['description'],
                        'unit_weight'  => $item['unit_weight'],
                        'unit_amount'  => $item['unit_amount'],
                        'quantity'     => $item['quantity'],
                    ];
                })->toArray();

                $payload = [
                    'sender_address_code'    => $data['sender_postal'],
                    'reciever_address_code'  => $receiverPostal,
                    'pickup_date'            => now()->format('Y-m-d'),
                    'category_id'            => 2178251,
                    'package_items'          => $packageItems,
                    'package_dimension'      => $dimension,
                    'service_type'           => 'pickup',
                    'delivery_instructions'  => 'Handle with care.',
                ];

                $response = Http::withToken(env('SHIPBUBBLE_API_KEY'))
                    ->post('https://api.shipbubble.com/v1/shipping/fetch_rates', $payload);

                if (!$response->ok()) {
                    return response()->json([
                        'success' => false,
                        'message' => "Shipping API error for seller $sellerId: " . $response->body()
                    ], 500);
                }

                $shippingDetails[$sellerId] = $response->json();
            }

            Log::error('Shipping' , $shippingDetails);        
            return response()->json([
                'success' => true,
                'shipping_rates' => $shippingDetails,
            ]);

        } catch (\Exception $e) {
            Log::error('Exception in calculateShipping', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    private function calculateMaxDimension($items)
    {
        $max = ['length' => 0, 'width' => 0, 'height' => 0];

        foreach ($items as $item) {
            $d = $item['dimension'];
            $max['length'] = max($max['length'], $d['length']);
            $max['width']  = max($max['width'], $d['width']);
            $max['height'] = max($max['height'], $d['height']);
        }

        return $max;
    }


}
