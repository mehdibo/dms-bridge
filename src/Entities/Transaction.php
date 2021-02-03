<?php


namespace Mehdibo\DpsBridge\Entities;


class Transaction implements TransactionInterface
{
    private float $amount;
    private string $uuid;
    private string $senderId;
    private string $receiverId;
    private bool $isValid;


    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;
        return $this;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;
        return $this;
    }

    public function getSenderId(): string
    {
        return $this->senderId;
    }

    public function setSenderId(string $id): self
    {
        $this->senderId = $id;
        return $this;
    }

    public function getReceiverId(): string
    {
        return $this->receiverId;
    }

    public function setReceiverId(string $id): self
    {
        $this->receiverId = $id;
        return $this;
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }

    public function setIsValid(bool $valid): self
    {
        $this->isValid = $valid;
        return $this;
    }
}