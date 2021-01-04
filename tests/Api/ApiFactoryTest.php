<?php


namespace Mehdibo\DpsBridge\Tests\Api;


use Mehdibo\DpsBridge\Api\Api;
use Mehdibo\DpsBridge\Api\ApiFactory;
use PHPUnit\Framework\TestCase;

class ApiFactoryTest extends TestCase
{

    public function testCreateApi(): void
    {
        $api = ApiFactory::create(
            'https://www.example.com',
            'client_id',
            'client_secret',
            'redirect_uri',
            'https://www.example.com',
            'https://www.example.com',
            'https://www.example.com',
        );
        $this->assertInstanceOf(Api::class, $api);
    }

}