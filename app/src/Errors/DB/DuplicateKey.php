<?php

namespace App\Errors\DB;

use Exception;
use Throwable;

class DuplicateKey extends  Exception
{
    public function __construct(string $message = "key violates the unique key constraint", int $code = 400, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString(): string
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
