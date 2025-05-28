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

            // Group cart items by seller
            foreach ($cartItems as $cartItem) {
                $product = Product::find($cartItem->product_id);
                if (!$product || !$product->user) continue;

                $seller = $product->user;
                $startup = Setup::where('user_id', $seller->id)->first();
                if (!$startup || empty($startup->postal_code)) continue;

                $groupedItemsBySeller[$seller->id]['sender_postal'] = $startup->postal_code;

                $groupedItemsBySeller[$seller->id]['items'][] = [
                    'name'        => $product->name,
                    'description' => $product->description,
                    'unit_weight' => $product->weight ?? 1, // default 1kg if not set
                    'unit_amount' => $product->price,
                    'quantity'    => $cartItem->quantity,
                    'dimension'   => [
                        'length' => $product->length ?? 10,
                        'width'  => $product->width ?? 10,
                        'height' => $product->height ?? 10,
                    ],
                ];
            }

            $totalShipping = 0;
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
                        'message' => 'Failed to fetch rate for seller ID: ' . $sellerId,
                        'error'   => $response->body()
                    ], 500);
                }

                $rates = $response->json()['rates'] ?? [];
                $rate = collect($rates)->first();

                if ($rate && isset($rate['amount'])) {
                    $shippingDetails[] = [
                        'seller_id' => $sellerId,
                        'amount' => $rate['amount'],
                        'service_name' => $rate['service_name'] ?? 'N/A',
                    ];
                    $totalShipping += $rate['amount'];
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'No valid shipping rate found for one or more sellers.'
                    ], 500);
                }
            }

            return response()->json([
                'success' => true,
                'total_shipping' => $totalShipping,
                'breakdown' => $shippingDetails
            ]);

        } catch (\Exception $e) {
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
