<?php


namespace App\Exception\Request;


use Symfony\Component\HttpKernel\Exception\HttpException;

class JsonNotParsableException extends HttpException
{
    public function __construct()
    {
        parent::__construct(
            400,
            'Request body can not be parsed to json.'
        );
    }
}
