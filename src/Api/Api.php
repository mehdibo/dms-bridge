<?php


namespace Mehdibo\DmsBridge\Api;


use League\OAuth2\Client\Provider\AbstractProvider;
use Mehdibo\DmsBridge\Entities\Account;
use Mehdibo\DmsBridge\Entities\Transaction;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class Api
{

    private string $apiBase;

    private AbstractProvider $oauth;
    private HttpClientInterface $client;
    private bool $test;

    /**
     * Api constructor.
     * @param string $apiBase
     * @param AbstractProvider $oauthProvider
     * @param HttpClientInterface $client
     * @param bool $testing TRUE if it's a testing env, skips the oauth part
     */
    public function __construct(string $apiBase, AbstractProvider $oauthProvider, HttpClientInterface $client, bool $testing = false)
    {
        $this->apiBase = rtrim($apiBase, '/');
        $this->oauth = $oauthProvider;
        $this->client = $client;
        $this->test = $testing;
    }

    private function sendRequest(string $method, string $endpoint, array $payload = []): ResponseInterface
    {
        $token = 'dummy_token';
        if (!$this->test) {
            $token = $this->oauth->getAccessToken('client_credentials')->getToken();

        }
        return $this->client->request(
            $method,
            $this->apiBase.$endpoint,
            [
                'headers' => [
                    'Authorization' => 'Bearer '.$token
                ],
                'json' => $payload
            ]
        );
    }

    /**
     * @param string $accountId
     * @return Account
     */
    public function newAccount(string $accountId): ?Account
    {
        $resp = $this->sendRequest(
            'POST',
            '/api/account/add',
            [
                'local_identifier' => $accountId,
            ]
        );
        if ($resp->getStatusCode() === 200)
            return (new Account())->setIdentifier($accountId);
        return null;
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

    public function deposit(string $identifier, float $amount): void
    {
        $this->sendRequest(
            'POST',
            '/api/network/deposit',
            [
                'identifier' => $identifier,
                'asset' => $amount
            ]
        );
    }

    public function withdraw(string $identifier, float $amount): void
    {
        $this->sendRequest(
            'POST',
            '/api/network/withdraw',
            [
                'identifier' => $identifier,
                'asset' => $amount
            ]
        );
    }
}