<?php


namespace Mehdibo\DmsBridge\Tests\Api;


use League\OAuth2\Client\Provider\AbstractProvider;
use Mehdibo\DmsBridge\Api\Api;
use Mehdibo\DmsBridge\Entities\Account;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ApiTest extends TestCase
{

    private AbstractProvider $oauthProvider;

    public function setUp(): void
    {
        $stub = $this->createStub(AbstractProvider::class);
        $stub->method('getAccessToken')
            ->willReturn('access_token');
        $this->oauthProvider = $stub;
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
//  TODO:
//    public function testNewAccount(): void
//    {
//        $api = new Api(
//            'http://localhost',
//            $this->oauthProvider,
//            $this->createClient([])
//        );
//        $api->newAccount((new Account())->setIdentifier('account_identifier'));
//    }

    public function testGetAccount(): void
    {
        $data = [
            'local_identifier' => 'account_id',
            'timestamp' => '2020-12-30T16:11:26.175Z',

        ];
        $client = $this->createClient($data, 200);
    }
}