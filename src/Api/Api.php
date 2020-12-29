<?php


namespace Mehdibo\DmsBridge\Api;


use League\OAuth2\Client\Provider\GenericProvider;
use Mehdibo\DmsBridge\Entities\Account;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class Api
{

    private const API_ENDPOINT = 'https://eni3qpl7a3fc.x.pipedream.net';

    private GenericProvider $genericProvider;
    private HttpClientInterface $client;

    public function __construct(GenericProvider $genericProvider, HttpClientInterface $client)
    {
        $this->genericProvider = $genericProvider;
        $this->client = $client;
    }

    private function sendRequest(string $method, string $endpoint, array $payload): ResponseInterface
    {
        $token = $this->genericProvider->getAccessToken('client_credentials');
        return $this->client->request(
            $method,
            self::API_ENDPOINT.$endpoint,
            [
                'headers' => [
                    'Authorization' => 'Bearer '.$token->getToken()
                ],
                'json' => $payload
            ]
        );
    }

    public function newAccount(Account $account): void
    {
        $this->sendRequest(
            'POST',
            '/api/account/add',
            [
                'local_identifier' => $account->getIdentifier(),
            ]
        );
    }

}