<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Log\LoggerInterface;

class AramexService
{
    protected Client $client;
    protected string $baseUrl;
    protected string $userName;
    protected string $password;
    protected LoggerInterface $logger;

    public const HTTP_POST = 'POST';

    public function __construct(Client $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;

        // Retrieve values from environment variables for Aramex
        $this->baseUrl = env('ARAMEX_BASE_URL', 'https://api.aramex.net');
        $this->userName = env('ARAMEX_USERNAME', '');
        $this->password = env('ARAMEX_PASSWORD', '');
    }

    /**
     * General method to send requests to Aramex API.
     *
     * @param string $endpoint
     * @param array $data
     * @param string $method
     * @return array
     */
    protected function sendRequest(string $endpoint, array $data, string $method = self::HTTP_POST): array
    {
        $url = $this->baseUrl . $endpoint;

        try {
            $response = $this->client->request($method, $url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => $data
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            $this->logger->error('Aramex API request failed', [
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
        $endpoint = '/shippingapi/v1/getrates'; // Replace with actual Aramex rate endpoint
        return $this->sendRequest($endpoint, $shipmentDetails);
    }

    // Additional methods for creating shipments, tracking, etc. can be added here
}
