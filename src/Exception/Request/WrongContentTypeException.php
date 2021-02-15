<?php


namespace App\Exception\Request;


use Symfony\Component\HttpKernel\Exception\HttpException;

class WrongContentTypeException extends HttpException
{
    public function __construct(string $expectedContentType)
    {
        parent::__construct(
            415,
            "Content-Type header must be $expectedContentType"
        );
    }
}
