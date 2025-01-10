<?php

namespace App\Services;

use GuzzleHttp\Client;

class FedExService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getToken()
    {
        $url = env('FEDEX_TEST_URL') . '/oauth/token';
        $clientId = env('FEDEX_CLIENT_ID');
        $clientSecret = env('FEDEX_CLIENT_SECRET');

        try {
            $response = $this->client->post($url, [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'form_params' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => $clientId,
                    'client_secret' => $clientSecret,
                    'scope' => 'shipping',
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            if (isset($data['access_token'])) {
                return $data['access_token'];
            }

            throw new \Exception('Token not found in the response.');
        } catch (\Exception $e) {
            throw new \Exception('Failed to get FedEx token: ' . $e->getMessage());
        }
    }

    public function calculateShippingFee(array $data)
    {
        $token = $this->getToken();
        $url = 'https://apis-sandbox.fedex.com/rate/v1/rates/quotes';

        try {
            $response = $this->client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                ],
                'json' => $data,
            ]);

            $responseData = json_decode($response->getBody(), true);

            // Parse the response to get the shipping fee
            return $responseData['output'] ?? null;
        } catch (\Exception $e) {
            throw new \Exception('Failed to calculate shipping fee: ' . $e->getMessage());
        }
    }
}
