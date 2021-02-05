<?php


namespace Mehdibo\DpsBridge\Api;


use League\OAuth2\Client\Provider\GenericProvider;
use Symfony\Component\HttpClient\HttpClient;

class ApiFactory
{
    public static function create(
        string $apiBase,
        string $clientId,
        string $clientSecret,
        bool $testing = false
    ): ApiInterface
    {
        $apiBase = rtrim($apiBase, "/");
        $provider = new GenericProvider([
            'clientId'                => $clientId,
            'clientSecret'            => $clientSecret,
            'redirectUri'             => '',
            'urlAuthorize'            => '', // Doesnt apply as we will be using client_credentials grant
            'urlAccessToken'          => $apiBase.'/api/users/login',
            'urlResourceOwnerDetails' => $apiBase.'/api/users/me',
        ]);
        return new Api($apiBase, $provider, HttpClient::create(), $testing);
    }
}