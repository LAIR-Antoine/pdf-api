<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class GotenbergService
{
    private $client;
    private string $gotenbergUrl;

    public function __construct(HttpClientInterface $client, string $gotenbergUrl)
    {
        $this->client = $client;
        $this->gotenbergUrl = $gotenbergUrl;
    }

    public function convertUrlToPdf(string $url, string $outputPath): void
    {
        try {
            $response = $this->client->request('POST',  $this->gotenbergUrl, [
                'headers' => [
                    'Content-Type' => 'multipart/form-data'
                ],
                'body' => [
                    'url' => $url
                ]
            ]);

            if ($response->getStatusCode() === 200) {
                file_put_contents($outputPath, $response->getContent());
            }
        } catch (TransportExceptionInterface $e) {
            throw new \Exception($e->getMessage());
        }
    }
}