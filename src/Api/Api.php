<?php


namespace Mehdibo\DmsBridge\Api;


use League\OAuth2\Client\Provider\GenericProvider;
use Mehdibo\DmsBridge\Entities\Account;

class Api
{

    private GenericProvider $genericProvider;

    public function __construct(GenericProvider $genericProvider)
    {
        $this->genericProvider = $genericProvider;
    }

    public function newAccount(Account $account): void
    {
    }

}