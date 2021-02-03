<?php


namespace Mehdibo\DpsBridge\Entities;


interface AccountInterface
{
    public function getId():string;
    public function getBalance():float;

    /**
     * @return Transaction[]
     */
    public function getTransactions():array;
    public function getTimestamp():\DateTimeInterface;
}