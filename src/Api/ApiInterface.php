<?php


namespace Mehdibo\DpsBridge\Api;


use Mehdibo\DpsBridge\Entities\Account;
use Mehdibo\DpsBridge\Entities\AccountInterface;
use Mehdibo\DpsBridge\Entities\TransactionInterface;
use Mehdibo\DpsBridge\Exception\ApiRequestException;

interface ApiInterface
{
    /**
     * Add new account to DPS
     * @param string $accountId
     * @throws ApiRequestException
     */
    public function createAccount(string $accountId): void;

    /**
     * @param string $identifier
     * @return Account|null
     * @throws ApiRequestException
     */
    public function getAccount(string $identifier): ?AccountInterface;

    /**
     * @throws ApiRequestException
     */
    public function deposit(string $accountId, float $amount): void;

    /**
     * @throws ApiRequestException
     */
    public function withdraw(string $accountId, float $amount): void;

    /**
     * @throws ApiRequestException
     */
    public function newTransaction(TransactionInterface $transaction): void;
}