<?php
namespace App\Utils;

use DateInterval;
use DateTime;
use DateTimeZone;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Ramsey\Uuid\Uuid;

class Token
{
    public static $sub = null;
    public static $token_uuid = null;
    public static $exp = null;
    public static $iat = null;
    public static $nbf = null;

    /**
     * Create a new access token
     *
     * @param  string user_id The ID of the user
     * @return string
     **/
    public static function create(string $user_id): string
    {
        $private_key = base64_decode($_ENV['ACCESS_TOKEN_PRIVATE_KEY']);
        $ttl = new DateInterval($_ENV['ACCESS_TOKEN_EXPIRED_IN']);

        $now = new DateTime('now', new DateTimeZone('UTC'));
        $expires = clone $now;
        $expires->add($ttl);

        $tokenUUID = Uuid::uuid4();

        $payload = [
          'sub' => $user_id,
          'token_uuid' => $tokenUUID->toString(),
          'exp' => $expires->getTimestamp(),
          'iat' => $now->getTimestamp(),
          'nbf' => $now->getTimestamp()
        ];

        $jwt = JWT::encode($payload, $private_key, 'RS256');
        return $jwt;
    }

    /**
     * Decode the JWT token and populate the variables
     *
     * @param  string jwt The JWT token
     * @return bool
     **/
    public static function decode(string $jwt): bool
    {
        $public_key = base64_decode($_ENV['ACCESS_TOKEN_PUBLIC_KEY']);
        try {
            $decoded = JWT::decode($jwt, new Key($public_key, 'RS256'));
        } catch (Exception $e) {
            return false;
        }

        $decoded_array = (array) $decoded;

        self::$sub = $decoded_array['sub'];
        self::$token_uuid = $decoded_array['token_uuid'];
        self::$exp = $decoded_array['exp'];
        self::$iat = $decoded_array['iat'];
        self::$nbf = $decoded_array['nbf'];

        return true;
    }
}
