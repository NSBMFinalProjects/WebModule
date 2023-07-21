<?php

namespace App\Connnections;

use Exception;
use MongoDB;
use MongoDB\Database;

class Mongo
{
    private static $conn;

    public static function db(): ?Database
    {
        if (self::$conn) {
            return self::$conn;
        }

        $uri = $_ENV['MONGO_URL'];
        $database = $_ENV['MONGO_DB'];

        $client = new MongoDB\Client($uri);

        try {
            self::$conn = $client->selectDatabase($database);
            return self::$conn;
        } catch (Exception $e) {
            return null;
        }

    }
}
