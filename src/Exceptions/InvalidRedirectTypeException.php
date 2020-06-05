<?php


namespace SchGroup\Zotto\Exceptions;


use Throwable;

class InvalidRedirectTypeException extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $message = "Redirect type must be card type";
        parent::__construct($message, $code, $previous);
    }
}