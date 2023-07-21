<?php

namespace App\Connnections;

use PDO;

class DB
{
    private static $conn;

    /**
     * Creates a connection with the database
     *
     * @return PDO the connection to the database
     **/
    public static function db(): ?PDO
    {
        if (self::$conn) {
            return self::$conn;
        }

        $db_host = $_ENV['POSTGRES_HOST'];
        $db_name = $_ENV['POSTGRES_DB'];
        $db_user = $_ENV['POSTGRES_USER'];
        $db_password = $_ENV['POSTGRES_PASSWORD'];
        $db_port = $_ENV['POSTGRES_PORT'];

        $dsn = 'pgsql:host=' . $db_host . ';port=' . $db_port . ';dbname=' . $db_name . ';';
        self::$conn = new PDO(dsn: $dsn, username: $db_user, password: $db_password);
        if (self::$conn) {
            return self::$conn;
        }

        return null;
    }
}
