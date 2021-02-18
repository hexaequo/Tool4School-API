<?php


namespace App\Exception\Request;


use App\Exception\ApiException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class WrongContentTypeException extends ApiException
{
    public function __construct(string $expectedContentType)
    {
        parent::__construct(
            Response::HTTP_UNSUPPORTED_MEDIA_TYPE,
            "Content-Type header must be $expectedContentType"
        );
    }
}
