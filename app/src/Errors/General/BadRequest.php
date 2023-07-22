<?php
namespace App\Errors\General;


use Exception;
use Throwable;

class BadRequest extends  Exception
{
    public function __construct(string $message = "bad request", int $code = 400, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString(): string
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
