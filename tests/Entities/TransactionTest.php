<?php

namespace Mehdibo\DpsBridge\Tests\Entities;

use Mehdibo\DpsBridge\Entities\Transaction;
use PHPUnit\Framework\TestCase;

class TransactionTest extends TestCase
{
    private const DATA = [
        'asset' => 1337.42,
        'uuid'  => '143a3cd8-4aab-11eb-b378-0242ac130002',
        'src'   => 'src_identifier',
        'dst'   => 'dst_identifier',
        'valid' => true
    ];

    private Transaction $transaction;

    public function setUp(): void
    {
        $this->transaction = new Transaction();
        $this->transaction->setAmount(self::DATA['asset'])
            ->setUuid(self::DATA['uuid'])
            ->setSenderId(self::DATA['src'])
            ->setReceiverId(self::DATA['dst'])
            ->setIsValid(self::DATA['valid']);
    }

    public function testGetAsset(): void
    {
        $this->assertEquals(self::DATA['asset'], $this->transaction->getAsset());
    }

    public function testSetAsset(): void
    {
        $newValue = 50.02;
        $this->transaction->setAmount($newValue);
        $this->assertEquals($newValue, $this->transaction->getAsset());
    }

    public function testGetUuid(): void
    {
        $this->assertEquals(self::DATA['uuid'], $this->transaction->getUuid());
    }

    public function testSetUuid(): void
    {
        $newValue = 'f8d18112-4aab-11eb-b378-0242ac130002';
        $this->transaction->setUuid($newValue);
        $this->assertEquals($newValue, $this->transaction->getUuid());
    }

    public function testGetSenderId(): void
    {
        $this->assertEquals(self::DATA['src'], $this->transaction->getSenderId());
    }

    public function testSetSenderId(): void
    {
        $newValue = 'new_src';
        $this->transaction->setSenderId($newValue);
        $this->assertEquals($newValue, $this->transaction->getSenderId());
    }

    public function testGetReceiverId(): void
    {
        $this->assertEquals(self::DATA['dst'], $this->transaction->getReceiverId());
    }

    public function testSetReceiverId(): void
    {
        $newValue = 'new_dst';
        $this->transaction->setReceiverId($newValue);
        $this->assertEquals($newValue, $this->transaction->getReceiverId());
    }

    public function testIsValid(): void
    {
        $this->assertEquals(self::DATA['valid'], $this->transaction->isValid());
    }

    public function testSetIsValid(): void
    {
        $newValue = !self::DATA['valid'];
        $this->transaction->setIsValid($newValue);
        $this->assertEquals($newValue, $this->transaction->isValid());
    }
}
