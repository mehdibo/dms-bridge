<?php


namespace Mehdibo\DpsBridge\Entities;


interface TransactionInterface
{
    public function getAmount(): float;
    public function getUuid(): string;
    public function getSender(): ?AccountInterface;
    public function getReceiver(): ?AccountInterface;
    public function isValid(): bool;
}