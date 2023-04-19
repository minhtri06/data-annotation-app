<?php

namespace App\Exceptions;

use Exception;

class ApiException extends Exception
{
    public static function BadRequest($message = "Bad request")
    {
        return new self($message, 400);
    }

    public static function NotFound($message = "Not found")
    {
        return new self($message, 404);
    }

    public static function Unauthorized($message = "Unauthorized")
    {
        return new self($message, 401);
    }

    public static function Forbidden($message = "Forbidden")
    {
        return new self($message, 403);
    }
}
