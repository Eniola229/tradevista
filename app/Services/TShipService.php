<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Log\LoggerInterface;

class TShipService
{
    protected Client $client;
    protected string $baseUrl;
    protected string $apiKey;
    protected LoggerInterface $logger;

    public const HTTP_GET = 'GET';

    public function __construct(Client $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;

        // Retrieve values from environment variables for T-Ship
        $this->baseUrl = env('TSHIP_BASE_URL', 'https://api.terminal.africa');
        $this->apiKey = env('TSHIP_API_KEY', '');
    }

    /**
     * General method to send requests to T-Ship API.
     *
     * @param string $endpoint
     * @param array $queryParams
     * @param string $method
     * @return array
     */
    protected function sendRequest(string $endpoint, array $queryParams, string $method = self::HTTP_GET): array
    {
        $url = $this->baseUrl . $endpoint;

        try {
            $response = $this->client->request($method, $url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->apiKey,
                ],
                'query' => $queryParams,
            ]);

            // Decode the response body
            $decodedResponse = json_decode($response->getBody()->getContents(), true);

            // Log the API response
            $this->logger->info('API Response:', ['response' => $decodedResponse]);

            return $decodedResponse;
        } catch (RequestException $e) {
            // Log the error details
            $this->logger->error('T-Ship API request failed', [
                'endpoint' => $endpoint,
                'queryParams' => $queryParams,
                'error' => $e->getMessage(),
            ]);

            // If the exception has a response, log and return it
            if ($e->hasResponse()) {
                $errorResponse = json_decode($e->getResponse()->getBody()->getContents(), true);

                // Log the API error response
                $this->logger->error('API Error Response:', ['response' => $errorResponse]);

                return $errorResponse;
            }

            // Return a generic error message if no response exists
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
        $endpoint = '/v1/rates/shipment'; // Updated to the correct T-Ship rate endpoint
        return $this->sendRequest($endpoint, $shipmentDetails);
    }

    // Additional methods for creating shipments, tracking, etc. can be added here
}
