<?php


namespace Mehdibo\DpsBridge\Tests\Api;


use Mehdibo\DpsBridge\Api\Api;
use Mehdibo\DpsBridge\Api\ApiFactory;
use Mehdibo\DpsBridge\Api\ApiInterface;
use PHPUnit\Framework\TestCase;

class ApiFactoryTest extends TestCase
{

    public function testCreateApi(): void
    {
        $api = ApiFactory::create(
            'https://www.example.com',
            'client_id',
            'client_secret',
            true,
        );
        $this->assertInstanceOf(ApiInterface::class, $api);
    }

}