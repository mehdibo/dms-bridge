<?php


namespace Mehdibo\DpsBridge\Api;


use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Mehdibo\DpsBridge\Entities\Account;
use Mehdibo\DpsBridge\Entities\AccountInterface;
use Mehdibo\DpsBridge\Entities\Transaction;
use Mehdibo\DpsBridge\Exception\ApiRequestException;
use Mehdibo\DpsBridge\Exception\AuthenticationException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
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

    /**
     * @return ResponseInterface
     * @throws ApiRequestException
     */
    private function sendRequest(string $method, string $endpoint, array $payload = []): ResponseInterface
    {
        // OAuth is not activated in the testing environment
        $token = 'dummy_token';
        if (!$this->test) {
            try {
                $token = $this->oauth->getAccessToken('client_credentials')->getToken();
            } catch (IdentityProviderException $e) {
                $authException = new AuthenticationException($e->getMessage(), 0, $e);
                throw new ApiRequestException($e->getMessage(), 0, $authException);;
            }
        }
        try {
            $resp = $this->client->request(
                $method,
                $this->apiBase . $endpoint,
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token
                    ],
                    'json' => $payload
                ]
            );
            $statusCode = $resp->getStatusCode();
            if ($statusCode !== 200) {
                throw new ApiRequestException("Request failed ($statusCode): " . $resp->getContent(false));
            }
            return $resp;
        } catch (TransportExceptionInterface $e) {
            throw new ApiRequestException($e->getMessage(), 0, $e);
        }
    }

    /**
     * Add new account to DPS
     * @param string $accountId
     * @throws ApiRequestException
     */
    public function createAccount(string $accountId): void
    {
        $this->sendRequest(
            'POST',
            '/api/account/add',
            [
                'local_identifier' => $accountId,
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

    /**
     * @param string $identifier
     * @return float
     * @throws ApiRequestException
     */
    private function getAccountBalance(string $identifier): float
    {
        $response = $this->sendRequest(
            'GET',
            '/api/account/'.$identifier.'/balance'
        );
        $data = $response->toArray(false);
        if (!isset($data['asset'])) {
            throw new ApiRequestException("Couldn't find asset in response body");
        }
        return (float) $data['asset'];
    }

    /**
     * @param string $identifier
     * @return Account|null
     * @throws ApiRequestException
     */
    public function getAccount(string $identifier): ?AccountInterface
    {
        $resp = $this->sendRequest(
            'GET',
            '/api/account/'.$identifier.'/find'
        );
        $data = $resp->toArray(false);
        // Check expected keys
        $expectedKeys = ['local_identifier', 'transactions', 'timestamp'];
        foreach ($expectedKeys as $expectedKey) {
            if (!array_key_exists($expectedKey, $data)) {
                throw new ApiRequestException("Couldn't find $expectedKey in response body");
            }
        }
        $account = new Account();
        return $account->setIdentifier($data['local_identifier'])
            ->setTimestamp(new \DateTime($data['timestamp']))
            ->setTransactions($this->transactionsFactory($data['transactions']))
            ->setBalance($this->getAccountBalance($identifier));
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

    public function newTransaction(Transaction $transaction): void
    {
        $this->sendRequest(
            'POST',
            '/api/transaction/new',
            [
                'asset' => $transaction->getAsset(),
                'transaction_uuid' => $transaction->getUuid(),
                'source' => $transaction->getSrc(),
                'destination' => $transaction->getDst(),
                'valid' => $transaction->isValid(),
            ]
        );
    }
}