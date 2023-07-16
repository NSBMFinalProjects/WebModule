<?php
namespace App\Utils;

use Exception;
use GuzzleHttp\Client;

class OAuth
{

    /**
     * @param string clientID The Github OAuth application client ID
     * @param string clientSecret The Github OAuth application client secret
     * @param string state The URL that needs to be redirected to
     * @param string code The code provided by GitHub
     *
     * @return string The access_token
     **/
    public static function getGithubAccessToken(string $clientID, string $clientSecret, string $state, string $code): string
    {
        $client = new Client();
        $response = $client->get(
            'https://github.com/login/oauth/access_token', [
            'query' => [
            'client_id' => $clientID,
            'client_secret' => $clientSecret,
            'state' => $state,
            'code' => $code,
            ],
            'headers' => [
            'Accept'     => 'application/json',
            ]
            ],
        );

        if ($response->getStatusCode() != 200) {
            return "";
        }

        $array = json_decode($response->getBody()->getContents(), true);
        try {
            $access_token = $array["access_token"];
            return $access_token;
        } catch (Exception $e) {
            return "";
        }
    }

    public static function getGithubUser(string $access_token): mixed
    {
        $client = new Client();
        $response = $client->get(
            "https://api.github.com/user", [
              'headers' => [
                'Authorization' => 'Bearer ' . $access_token,
                'Accept'     => 'application/json',
              ]
            ]
        );

        if ($response->getStatusCode() !== 200) {
            return null;
        }

        $array = json_decode($response->getBody()->getContents(), true);
        return $array;
    }
}
