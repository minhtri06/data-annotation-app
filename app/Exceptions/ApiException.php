<?php

namespace App\Exceptions;

use Exception;

class ApiException extends Exception
{
    public static function badRequest($message = "Bad request")
    {
        return new self($message, 400);
    }

    public static function NotFound($message = "Not found")
    {
        return new self($message, 404);
    }
}
