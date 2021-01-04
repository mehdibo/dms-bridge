<?php


namespace Mehdibo\DmsBridge\Tests\Api;


use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Mehdibo\DmsBridge\Api\Api;
use Mehdibo\DmsBridge\Entities\Account;
use Mehdibo\DmsBridge\Entities\Transaction;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ApiTest extends TestCase
{

    private AbstractProvider $oauthProvider;

    public function setUp(): void
    {
        $tokenStub = $this->createStub(AccessTokenInterface::class);
        $tokenStub->method('getToken')
            ->willReturn('access_token');
        $providerStub = $this->createStub(AbstractProvider::class);
        $providerStub->method('getAccessToken')
            ->willReturn($tokenStub);
        $this->oauthProvider = $providerStub;
    }

    private function createApi(HttpClientInterface $client): Api
    {
        return new Api('http://localhost', $this->oauthProvider, $client);
    }

    /**
     * @param array $respBody
     * @param int $respStatusCode
     * @return HttpClientInterface
     */
    private function createClient(array $respBody, int $respStatusCode = 200): HttpClientInterface
    {
        $responseStub = $this->createStub(ResponseInterface::class);
        $responseStub->method('toArray')
            ->willReturn($respBody);
        $responseStub->method('getStatusCode')
            ->willReturn($respStatusCode);

        $clientStub = $this->createStub(HttpClientInterface::class);
        $clientStub->method('request')
            ->willReturn($responseStub);
        return $clientStub;
    }

    /**
     * This test doesnt have any assertion as the function doesnt return any value
     */
    public function testNewAccount(): void
    {
        $api = new Api(
            'http://localhost',
            $this->oauthProvider,
            $this->createClient([])
        );
        $result = $api->newAccount('account_identifier');
        $this->assertInstanceOf(Account::class, $result);
        $this->assertEquals('account_identifier', $result->getIdentifier());
    }

    public function testGetAccount(): void
    {
        $data = [
            'local_identifier' => 'account_id',
            'timestamp' => '2020-12-30T16:11:26.175Z',
            'transactions' => [
                [
                    'asset' => 10.2,
                    'transaction_uuid' => 'uuid_1',
                    'source' => 'src_thing',
                    'destination' => 'dest_thing',
                    'valid' => true,
                ],
                [
                    'asset' => 18.2,
                    'transaction_uuid' => 'uuid_2',
                    'source' => 'src_thing_2',
                    'destination' => 'dest_thing_1',
                    'valid' => false,
                ],
            ],
            'asset' => 133.7,
        ];
        $client = $this->createClient($data);
        $api = $this->createApi($client);
        $account = $api->getAccount('test_identifier');
        $this->assertInstanceOf(Account::class, $account);
        $this->assertEquals($data['local_identifier'], $account->getIdentifier());
        $this->assertEquals(new \DateTime($data['timestamp']), $account->getTimestamp());
        $this->assertCount(count($data['transactions']), $account->getTransactions());
        $i = 0;
        foreach ($account->getTransactions() as $transaction)
        {
            $this->assertInstanceOf(Transaction::class, $transaction);
            $this->assertEquals($data['transactions'][$i]['asset'], $transaction->getAsset());
            $this->assertEquals($data['transactions'][$i]['transaction_uuid'], $transaction->getUuid());
            $this->assertEquals($data['transactions'][$i]['source'], $transaction->getSrc());
            $this->assertEquals($data['transactions'][$i]['destination'], $transaction->getDst());
            $this->assertEquals($data['transactions'][$i]['valid'], $transaction->isValid());
            $i++;
        }
        $this->assertEquals($data['asset'], $account->getBalance());
    }

    public function testDeposit(): void
    {
        $client = $this->createClient([]);
        $api = $this->createApi($client);
        $api->deposit('account_id', 15.90);
    }

    public function testWithdraw(): void
    {
        $client = $this->createClient([]);
        $api = $this->createApi($client);
        $api->withdraw('account_id', 15.90);
    }
    public function testNewTransaction(): void
    {
        $client = $this->createClient([]);
        $api = $this->createApi($client);
        $transaction = new Transaction();
        $transaction->setValid(true)
            ->setDst('dst_id')
            ->setSrc('src_id')
            ->setUuid('uuid_1')
            ->setAsset(95.23);
        $api->newTransaction($transaction);
    }
}