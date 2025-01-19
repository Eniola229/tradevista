<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Log\LoggerInterface;

class FedExService
{
    protected Client $client;
    protected string $baseUrl;
    protected string $key;
    protected string $password;
    protected string $accountNumber;
    protected string $meterNumber;
    protected LoggerInterface $logger;

    public const HTTP_POST = 'POST';
    public const HTTP_GET = 'GET';

    public function __construct(Client $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;

        // Retrieve values from environment variables
        $this->baseUrl = env('FEDEX_BASE_URL', 'https://apis-sandbox.fedex.com');
        $this->key = env('FEDEX_API_KEY', '');
        $this->password = env('FEDEX_API_PASSWORD', '');
        $this->accountNumber = env('FEDEX_ACCOUNT_NUMBER', '');
        $this->meterNumber = env('FEDEX_METER_NUMBER', '');
    }

    /**
     * Method to get an access token from FedEx OAuth endpoint.
     *
     * @return string|null
     */
    public function getAccessToken(): ?string
    {
        $url = $this->baseUrl . '/oauth/token';
        $body = [
            'grant_type' => 'client_credentials',
            'client_id' => $this->key,
            'client_secret' => $this->password
        ];

        try {
            $response = $this->client->post($url, [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'form_params' => $body
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            return $data['access_token'] ?? null;
        } catch (RequestException $e) {
            $this->logger->error('FedEx OAuth request failed', [
                'url' => $url,
                'body' => $body,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * General method to send requests to FedEx API.
     *
     * @param string $endpoint
     * @param array $data
     * @param string $method
     * @return array
     */
    protected function sendRequest(string $endpoint, array $data, string $method = self::HTTP_POST): array
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return ['error' => 'Unable to obtain access token'];
        }

        $url = $this->baseUrl . $endpoint;

        try {
            $response = $this->client->request($method, $url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $accessToken,
                    'X-locale' => 'en_US', // Optional
                ],
                'json' => $data
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            $this->logger->error('FedEx API request failed', [
                'endpoint' => $endpoint,
                'data' => $data,
                'error' => $e->getMessage(),
            ]);

            if ($e->hasResponse()) {
                return json_decode($e->getResponse()->getBody()->getContents(), true);
            }

            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Method to get rates.
     *
     * @param array $shipmentDetails
     * @return array
     */
    public function getRates(array $shipmentDetails): array
    {
        $endpoint = '/rate/v1/rates/quotes';
        return $this->sendRequest($endpoint, $shipmentDetails);
    }

    public function getTransactionDetails(string $transactionId): array
    {
        $endpoint = '/rate/v1/transactions/' . $transactionId; // Update the endpoint if required
        return $this->sendRequest($endpoint, [], self::HTTP_GET);
    }

    /**
     * Method to create a shipment.
     *
     * @param array $shipmentDetails
     * @return array
     */
    public function createShipment(array $shipmentDetails): array
    {
        $endpoint = '/ship/v1/shipments';
        return $this->sendRequest($endpoint, $shipmentDetails);
    }

    /**
     * Method to track a shipment.
     *
     * @param string $trackingNumber
     * @return array
     */
    public function trackShipment(string $trackingNumber): array
    {
        $endpoint = '/track/v1/trackingnumbers';
        $data = [
            'trackingInfo' => [
                [
                    'trackingNumberInfo' => [
                        'trackingNumber' => $trackingNumber
                    ]
                ]
            ]
        ];

        return $this->sendRequest($endpoint, $data);
    }
}
