<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\TopshipService; 
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    protected $topshipService;

    public function __construct(TopshipService $topshipService)
    {
        $this->topshipService = $topshipService;
    }

    /**
     * Get pickup rates with hardcoded test data.
     */
    public function getPickupRates()
    {
        // Hardcoded test data for pickup rates
        $shipmentDetails = [
            'shipmentDetail' => [
                'senderDetails' => [
                    'cityName' => 'Ibadan',
                    'countryCode' => 'NG', // Nigeria
                ],
                'receiverDetails' => [
                    'cityName' => 'Lagos',
                    'countryCode' => 'NG', // Nigeria
                ],
                'totalWeight' => 5.0, // Weight in kilograms
            ],
        ];

        // Call the Topship service to get pickup rates
        $rates = $this->topshipService->getPickupRates($shipmentDetails);

        // Return the rates as JSON
        return response()->json($rates);
    }
    /**
     * Create a shipment with test data.
     */
    public function createShipment()
    {
        // Hardcoded test data for creating a shipment from Ibadan to Lagos
        $payload = [
            'origin' => 'Ibadan, Nigeria',
            'destination' => 'Lagos, Nigeria',
            'weight' => 5.0, // 5 kg
            'recipient' => [
                'name' => 'John Doe',
                'phone' => '08012345678',
                'address' => 'No 15, Marina Street, Lagos',
            ],
            'sender' => [
                'name' => 'Jane Smith',
                'phone' => '08123456789',
                'address' => 'No 10, Mokola Road, Ibadan',
            ],
        ];

        $shipment = $this->topshipService->createShipment($payload);

        return response()->json($shipment);
    }

    /**
     * Track a shipment with test data.
     */
    public function trackShipment()
    {
        // Hardcoded test tracking ID
        $trackingId = 'TS1234567890'; // Replace with a valid test tracking ID from Topship

        $tracking = $this->topshipService->trackShipment($trackingId);

        return response()->json($tracking);
    }
}
