<?php
namespace App\Utils;

class GetRoutes
{
    public static $routes_path = '/var/www/html/public/routes';

    public function __construct(string $routes_path = '/var/www/html/public/routes')
    {
        self::$routes_path = $routes_path;
    }

    public static function getPath(string $path = '/'): string
    {
        if (str_ends_with(haystack: $path, needle: '/')) {
            return self::$routes_path . $path . 'index.php';
        } else {
            return self::$routes_path . $path;
        }
    }
}
