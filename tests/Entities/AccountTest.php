<?php

namespace Mehdibo\DpsBridge\Tests\Entities;

use Mehdibo\DpsBridge\Entities\Account;
use Mehdibo\DpsBridge\Entities\Transaction;
use PHPUnit\Framework\TestCase;

class AccountTest extends TestCase
{

    private const DATA = [
        'identifier' => 'account_identifier',
        'balance' => 1300.37,
        'transactions_count' => 10,
    ];

    private \DateTimeInterface $accountTimestamp;

    private Account $account;

    public function setUp(): void
    {
        $this->accountTimestamp = new \DateTime('now');
        $this->account = new Account();
        $this->account->setIdentifier(self::DATA['identifier'])
            ->setBalance(self::DATA['balance'])
            ->setTimestamp($this->accountTimestamp)
            ->setTransactions($this->createTransactions(self::DATA['transactions_count']));

    }

    /**
     * @param int $count
     * @return Transaction[]
     */
    private function createTransactions(int $count): array
    {
        $transactions = [];
        for ($i = 0; $i < $count; $i++) {
            $transactions[] = new Transaction();
        }
        return $transactions;
    }

    public function testGetTimestamp()
    {
        $this->assertEquals($this->accountTimestamp, $this->account->getTimestamp());
    }

    public function testSetTimestamp()
    {
        $newValue = new \DateTime('2020-12-29 13:37:00');
        $this->account->setTimestamp($newValue);
        $this->assertEquals($newValue, $this->account->getTimestamp());
        $this->assertNotEquals($this->accountTimestamp, $this->account->getTimestamp());
    }

    public function testGetBalance()
    {
        $this->assertEquals(self::DATA['balance'], $this->account->getBalance());
    }

    public function testSetBalance()
    {
        $newValue = 4242.37;
        $this->account->setBalance($newValue);
        $this->assertEquals($newValue, $this->account->getBalance());
    }

    public function testGetIdentifier()
    {
        $this->assertEquals(self::DATA['identifier'], $this->account->getIdentifier());
    }

    public function testSetIdentifier()
    {
        $newValue = 'new_identifier';
        $this->account->setIdentifier($newValue);
        $this->assertEquals($newValue, $this->account->getIdentifier());
    }

    public function testGetTransactions()
    {
        $this->assertCount(self::DATA['transactions_count'], $this->account->getTransactions());
        foreach ($this->account->getTransactions() as $transaction)
        {
            $this->assertInstanceOf(Transaction::class, $transaction);
        }
    }

    public function testSetTransactions()
    {
        $newCount = self::DATA['transactions_count'] + 5;
        $transactions = $this->createTransactions($newCount);
        $this->account->setTransactions($transactions);
        $this->assertCount($newCount, $this->account->getTransactions());
        foreach ($this->account->getTransactions() as $transaction)
        {
            $this->assertInstanceOf(Transaction::class, $transaction);
        }
    }
}
