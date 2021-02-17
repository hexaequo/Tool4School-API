<?php


namespace App\Exception;


use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiException extends HttpException
{
    public function __construct($code, $message)
    {
        parent::__construct(
            $code,
            $message
        );
    }
}
