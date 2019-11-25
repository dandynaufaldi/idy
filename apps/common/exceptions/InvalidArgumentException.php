<?php

namespace Idy\Common\Exceptions;

use Exception;

class InvalidArgumentException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}