<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class TopshipService
{
    protected $client;
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.topship.api_key');
        $this->baseUrl = config('services.topship.base_url');
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Authorization' => "Bearer {$this->apiKey}",
                'Accept' => 'application/json',
            ],
        ]);
    }

    /**
     * Get pickup rates.
     */
    public function getPickupRates(array $shipmentDetails)
    {
        try {
            $response = $this->client->post('https://api.terminal.africa/v1/rates/shipment', [
                'json' => $shipmentDetails,
            ]);

            return json_decode($response, true);
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                return json_decode($e->getResponse()->getBody(), true);
            }

            return ['error' => 'An error occurred while fetching pickup rates.'];
        }
    }
}
