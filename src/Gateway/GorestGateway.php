<?php

namespace App\Gateway;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class GorestGateway
{
    public function __construct(private HttpClientInterface $client)
    {
    }

    public function getUsers(): array
    {
        $response = $this->client->request(
            'GET',
            'https://gorest.co.in/public/v2/users'
        );

        return json_decode($response->getContent(), true);
    }

    public function getPosts(): array
    {
        $response =  $this->client->request(
            'GET',
            'https://gorest.co.in/public/v2/posts'
        );

        return json_decode($response->getContent(), true);
    }
}