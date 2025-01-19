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

    public function getRates(Request $request)
    {
        // Hardcoded test data with rateRequestType
        $testData = [
            'origin' => [
                'city' => 'New York',
                'state' => 'NY',
                'countryCode' => 'US',
                'postalCode' => '10001',
            ],
            'destination' => [
                'city' => 'Los Angeles',
                'state' => 'CA',
                'countryCode' => 'US',
                'postalCode' => '90001',
            ],
            'weight' => 5, // in KG
            'dimensions' => [
                'length' => 30, // in CM
                'width' => 20,  // in CM
                'height' => 15, // in CM
            ],
            'pickupType' => 'DROPOFF_AT_FEDEX_LOCATION', // Add pickup type
            'rateRequestType' => ['ACCOUNT'], // Specify rate request type
        ];

        // Build the payload for the FedEx API
        $data = [
            'accountNumber' => [
                'value' => env('FEDEX_ACCOUNT_NUMBER'),
            ],
            'requestedShipment' => [
                'shipper' => [
                    'address' => $testData['origin'],
                ],
                'recipient' => [
                    'address' => $testData['destination'],
                ],
                'pickupType' => $testData['pickupType'],
                'rateRequestType' => $testData['rateRequestType'], // Include rate request type
                'requestedPackageLineItems' => [
                    [
                        'weight' => [
                            'value' => $testData['weight'],
                            'units' => 'KG',
                        ],
                        'dimensions' => [
                            'length' => $testData['dimensions']['length'],
                            'width' => $testData['dimensions']['width'],
                            'height' => $testData['dimensions']['height'],
                            'units' => 'CM',
                        ],
                    ],
                ],
            ],
        ];

        try {
            $shippingFee = $this->fedExService->getRates($data);

            return response()->json($shippingFee);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


}
