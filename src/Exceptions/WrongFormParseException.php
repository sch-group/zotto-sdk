<?php


namespace SchGroup\Zotto\Exceptions;


use Throwable;

class WrongFormParseException extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $message = "Empty form parsed";

        parent::__construct($message, $code, $previous);
    }
}