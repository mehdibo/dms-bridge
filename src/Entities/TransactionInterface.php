<?php


namespace Mehdibo\DpsBridge\Entities;


interface TransactionInterface
{
    public function getAsset(): float;
    public function getUuid(): string;
    public function getSenderId(): string;
    public function getReceiverId(): string;
    public function isValid(): bool;
}