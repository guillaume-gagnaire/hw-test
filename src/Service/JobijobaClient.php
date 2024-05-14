<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class JobijobaClient {
    private $client;
    private $clientId;
    private $clientSecret;

    public function __construct(HttpClientInterface $client, $clientId, $clientSecret) {
        $this->client = $client;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    private function retrieveToken($country = 'fr') {
        $response = $this->client->request('POST', "https://api.jobijoba.com/v3/$country/login", [
            'body' => json_encode([
                'client_id' => $this->clientId, 
                'client_secret' => $this->clientSecret
            ])
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Cannot authenticate to Jobijoba');
        }

        $content = $response->toArray();

        return $content['token'];
    }

    public function getJobs($page = 1, $limit = 10, $country = 'fr'): array {
        $token = $this->retrieveToken($country);
        $params = [
            'what' => '',
            'where' => 'Bordeaux',
            'page' => $page,
            'limit' => $limit
        ];

        $response = $this->client->request('GET', "https://api.jobijoba.com/v3/$country/ads/search?" . http_build_query($params), [
            'headers' => [
                "Authorization: Bearer $token"
            ]
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Cannot retrieve jobs');
        }

        $jobs = $response->toArray();

        return $jobs['data'];
    }
}