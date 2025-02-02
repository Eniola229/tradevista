<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class UPSService
{
    protected $client;
    protected $apiBaseUrl;
    protected $tokenUrl;
    protected $clientId;
    protected $clientSecret;
    protected $merchantId;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->apiBaseUrl = config('ups.base_url');
        $this->tokenUrl = config('ups.token_url');
        $this->clientId = config('ups.client_id');
        $this->clientSecret = config('ups.client_secret');
        $this->merchantId = config('ups.merchant_id');
    }

    /**
     * Get the UPS OAuth access token via client credentials.
     *
     * @return string
     */
    public function getAccessToken()
    {
        try {
            $response = $this->client->post($this->tokenUrl, [
                'auth' => [$this->clientId, $this->clientSecret],
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'form_params' => [
                    'grant_type' => 'client_credentials',
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            return $data['access_token'];
        } catch (RequestException $e) {
            Log::error('Error fetching UPS access token: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get shipping rates from UPS API.
     * 
     * @param array $shipment
     * @param string $transactionId
     * @param string $requestOption
     * @param string $additionalInfo
     * @return array
     */
    public function getRate($shipment, $transactionId, $requestOption = 'Shop')
    {
        try {
            $accessToken = $this->getAccessToken();

            $response = $this->client->post($this->apiBaseUrl . "/rating/v2403/{$requestOption}", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                    'transId' => $transactionId,
                ],
                'json' => [
                    'RateRequest' => [
                        'Request' => [
                            'TransactionReference' => [
                                'TransactionIdentifier' => $transactionId,
                            ],
                        ],
                        'Shipment' => $shipment
                    ],
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            Log::error('Error fetching UPS rate: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create a shipment using UPS API.
     *
     * @param array $shipmentDetails
     * @param string $transactionId
     * @return array
     */
    public function createShipment(array $shipmentDetails, $transactionId)
    {
        try {
            $accessToken = $this->getAccessToken();

            $response = $this->client->post($this->apiBaseUrl . '/shipments/v1/ship', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                    'transId' => $transactionId,
                    'transactionSrc' => 'testing',
                ],
                'json' => $shipmentDetails,
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            Log::error('Error creating UPS shipment: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Track a shipment using UPS API.
     *
     * @param string $trackingNumber
     * @param string $transactionId
     * @return array
     */
    public function trackShipment($trackingNumber, $transactionId)
    {
        try {
            $accessToken = $this->getAccessToken();

            $response = $this->client->get($this->apiBaseUrl . "/track/v1/details/{$trackingNumber}?locale=en_US&returnSignature=false&returnMilestones=false&returnPOD=false", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'transId' => $transactionId,
                    'transactionSrc' => 'testing',
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            Log::error('Error tracking UPS shipment: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Validate an address using UPS API.
     *
     * @param array $addressDetails
     * @param string $version
     * @param string $requestOption
     * @return array
     */
    public function validateAddress(array $addressDetails, $version = 'v2', $requestOption = '1', $regionalRequestIndicator = 'False', $maxCandidateListSize = 1)
    {
        try {
            $accessToken = $this->getAccessToken();

            $response = $this->client->post($this->apiBaseUrl . "/addressvalidation/{$version}/{$requestOption}", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'query' => [
                    'regionalrequestindicator' => $regionalRequestIndicator,
                    'maximumcandidatelistsize' => $maxCandidateListSize,
                ],
                'json' => [
                    'XAVRequest' => $addressDetails,
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            Log::error('Error validating UPS address: ' . $e->getMessage());
            throw $e;
        }
    }

}

