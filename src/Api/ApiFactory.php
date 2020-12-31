<?php


namespace Mehdibo\DmsBridge\Api;


use League\OAuth2\Client\Provider\GenericProvider;
use Symfony\Component\HttpClient\HttpClient;

class ApiFactory
{
    public static function create(
        string $apiBase,
        string $clientId,
        string $clientSecret,
        string $redirectUri,
        string $urlAuthorize,
        string $urlAccessToken,
        string $urlResourceOwnerDetails
    ): Api
    {
        $provider = new GenericProvider([
            'clientId'                => $clientId,
            'clientSecret'            => $clientSecret,
            'redirectUri'             => $redirectUri,
            'urlAuthorize'            => $urlAuthorize,
            'urlAccessToken'          => $urlAccessToken,
            'urlResourceOwnerDetails' => $urlResourceOwnerDetails,
        ]);
        return new Api($apiBase, $provider, HttpClient::create());
    }
}