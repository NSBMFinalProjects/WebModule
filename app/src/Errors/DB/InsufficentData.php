<?php
namespace App\Errors\DB;


use Exception;
use Throwable;

class InsufficentData extends  Exception
{
    public function __construct(string $message = "provide all the paramters", int $code = 400, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString(): string
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
