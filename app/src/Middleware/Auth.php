<?php
namespace App\Middleware;

use App\Models\User;
use App\Utils\Token;

class Auth
{
    /**
     * A middleware to check wether the user is authenticated or not
     *
     * @return bool
     **/
    public static function isAuthed(): bool
    {
        if (!array_key_exists(key: 'session', array: $_COOKIE)) {
            return false;
        }

        $session = $_COOKIE['session'];
        $verified = Token::decode($session);
        if (!$verified) {
            return false;
        }

        return true;
    }
        /**
         * A middleware to check wether the user is the admin
         *
         * @return bool
         **/
    public static function isAdmin(): bool
    {
        if (!self::isAuthed()) {
            return false;
        }

        $session = $_COOKIE['session'];
        $verified = Token::decode($session);
        if (!$verified) {
            return false;
        }

        $user = User;
        return $user->getAdminStatus(Token::$sub);
    }

}
