<?php

namespace Mehdibo\DmsBridge\Tests\Entities;

use Mehdibo\DmsBridge\Entities\Transaction;
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
        $this->transaction->setAsset(self::DATA['asset'])
            ->setUuid(self::DATA['uuid'])
            ->setSrc(self::DATA['src'])
            ->setDst(self::DATA['dst'])
            ->setValid(self::DATA['valid']);
    }

    public function testGetAsset(): void
    {
        $this->assertEquals(self::DATA['asset'], $this->transaction->getAsset());
    }

    public function testSetAsset(): void
    {
        $newValue = 50.02;
        $this->transaction->setAsset($newValue);
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

    public function testGetSrc(): void
    {
        $this->assertEquals(self::DATA['src'], $this->transaction->getSrc());
    }

    public function testSetSrc(): void
    {
        $newValue = 'new_src';
        $this->transaction->setSrc($newValue);
        $this->assertEquals($newValue, $this->transaction->getSrc());
    }

    public function testGetDst(): void
    {
        $this->assertEquals(self::DATA['dst'], $this->transaction->getDst());
    }

    public function testSetDst(): void
    {
        $newValue = 'new_dst';
        $this->transaction->setDst($newValue);
        $this->assertEquals($newValue, $this->transaction->getDst());
    }

    public function testIsValid(): void
    {
        $this->assertEquals(self::DATA['valid'], $this->transaction->isValid());
    }

    public function testSetValid(): void
    {
        $newValue = !self::DATA['valid'];
        $this->transaction->setValid($newValue);
        $this->assertEquals($newValue, $this->transaction->isValid());
    }
}
