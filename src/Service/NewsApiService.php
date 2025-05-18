<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class NewsApiService
{
    private $client;
    private $apiKey;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
        $this->apiKey = '9fd46b83a6844dab890645ee9ac97ee5'; // Ta clé API
    }

    public function getFootballNews($pageSize = 12)
    {
        $url = 'https://newsapi.org/v2/everything';
        $response = $this->client->request('GET', $url, [
            'query' => [
                'q' => 'football',
                'language' => 'fr',
                'pageSize' => $pageSize,
                'apiKey' => $this->apiKey,
            ],
        ]);
        return $response->toArray();
    }
}