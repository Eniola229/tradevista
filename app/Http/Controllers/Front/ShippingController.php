<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

use App\Services\FedExService;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    protected $fedExService;

    public function __construct(FedExService $fedExService)
    {
        $this->fedExService = $fedExService;
    }

    public function calculateFee(Request $request)
    {
        $validated = $request->validate([
            'origin' => 'required|array',
            'destination' => 'required|array',
            'weight' => 'required|numeric',
            'dimensions' => 'required|array',
        ]);

        $data = [
            'accountNumber' => [
                'value' => env('FEDEX_ACCOUNT_NUMBER'),
            ],
            'requestedShipment' => [
                'shipper' => [
                    'address' => $validated['origin'],
                ],
                'recipient' => [
                    'address' => $validated['destination'],
                ],
                'requestedPackageLineItems' => [
                    [
                        'weight' => [
                            'value' => $validated['weight'],
                            'units' => 'KG',
                        ],
                        'dimensions' => [
                            'length' => $validated['dimensions']['length'],
                            'width' => $validated['dimensions']['width'],
                            'height' => $validated['dimensions']['height'],
                            'units' => 'CM',
                        ],
                    ],
                ],
            ],
        ];

        try {
            $shippingFee = $this->fedExService->calculateShippingFee($data);

            return response()->json([
                'shipping_fee' => $shippingFee,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
