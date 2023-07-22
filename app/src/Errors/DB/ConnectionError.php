<?php
namespace App\Errors\DB;


use Exception;
use Throwable;

class ConnectionError extends  Exception
{
    public function __construct(string $message = "internal server error", int $code = 500, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString(): string
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
