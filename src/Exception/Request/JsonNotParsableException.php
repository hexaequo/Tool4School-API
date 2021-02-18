<?php


namespace App\Exception\Request;


use App\Exception\ApiException;
use Symfony\Component\HttpFoundation\Response;

class JsonNotParsableException extends ApiException
{
    public function __construct()
    {
        parent::__construct(
            Response::HTTP_BAD_REQUEST,
            'Request body can not be parsed to json.'
        );
    }
}
