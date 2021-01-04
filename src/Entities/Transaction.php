<?php


namespace Mehdibo\DpsBridge\Entities;


class Transaction
{
    private float $asset;

    private string $uuid;

    private string $src;

    private string $dst;

    private bool $valid;

    /**
     * @return float
     */
    public function getAsset(): float
    {
        return $this->asset;
    }

    /**
     * @param float $asset
     * @return Transaction
     */
    public function setAsset(float $asset): Transaction
    {
        $this->asset = $asset;
        return $this;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     * @return Transaction
     */
    public function setUuid(string $uuid): Transaction
    {
        $this->uuid = $uuid;
        return $this;
    }

    /**
     * @return string
     */
    public function getSrc(): string
    {
        return $this->src;
    }

    /**
     * @param string $src
     * @return Transaction
     */
    public function setSrc(string $src): Transaction
    {
        $this->src = $src;
        return $this;
    }

    /**
     * @return string
     */
    public function getDst(): string
    {
        return $this->dst;
    }

    /**
     * @param string $dst
     * @return Transaction
     */
    public function setDst(string $dst): Transaction
    {
        $this->dst = $dst;
        return $this;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->valid;
    }

    /**
     * @param bool $valid
     * @return Transaction
     */
    public function setValid(bool $valid): Transaction
    {
        $this->valid = $valid;
        return $this;
    }


}