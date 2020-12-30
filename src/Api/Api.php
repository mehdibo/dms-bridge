<?php


namespace Mehdibo\DmsBridge\Api;


use League\OAuth2\Client\Provider\AbstractProvider;
use Mehdibo\DmsBridge\Entities\Account;
use Mehdibo\DmsBridge\Entities\Transaction;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class Api
{

    private const API_ENDPOINT = 'http://10.25.1.231:8000';

    private AbstractProvider $oauth;
    private HttpClientInterface $client;

    public function __construct(AbstractProvider $oauthProvider, HttpClientInterface $client)
    {
        $this->oauth = $oauthProvider;
        $this->client = $client;
    }

    private function sendRequest(string $method, string $endpoint, array $payload = []): ResponseInterface
    {
//        $token = $this->oauth->getAccessToken('client_credentials');
        $response = $this->client->request(
            $method,
            self::API_ENDPOINT.$endpoint,
            [
                'headers' => [
//                    'Authorization' => 'Bearer '.$token->getToken()
                ],
                'json' => $payload
            ]
        );
        var_dump($response->getStatusCode());
        var_dump($response->getContent());
        return $response;
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

    /**
     * @param array $rawTransactions
     * @return Transaction[]
     */
    private function transactionsFactory(array $rawTransactions): array
    {
        $transactions = [];
        foreach ($rawTransactions as $rawTransaction) {
            $transactions[] = (new Transaction())->setAsset($rawTransaction['asset'])
                ->setUuid($rawTransaction['transaction_uuid'])
                ->setSrc($rawTransaction['source'])
                ->setDst($rawTransaction['destination'])
                ->setValid($rawTransaction['valid']);
        }
        return $transactions;
    }

    private function getAccountBalance(string $identifier): float
    {
        $response = $this->sendRequest(
            'GET',
            '/api/account/'.$identifier.'/balance'
        );
        if ($response->getStatusCode() !== 200)
            return 0.0;
        return $response->toArray()['asset'];
    }

    public function getAccount(string $identifier): ?Account
    {
        $response = $this->sendRequest(
            'GET',
            '/api/account/'.$identifier.'/find'
        );
        if ($response->getStatusCode() !== 200)
            return null;
        $data = $response->toArray();
        $account = new Account();
        $account->setIdentifier($data['local_identifier'])
            ->setTimestamp(new \DateTime($data['timestamp']))
            ->setTransactions($this->transactionsFactory($data['transactions']))
            ->setBalance($this->getAccountBalance($identifier));
        return $account;
    }
}