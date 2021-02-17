<?php


namespace App\Exception\Request;


use App\Exception\ApiException;

class JsonNotParsableException extends ApiException
{
    public function __construct()
    {
        parent::__construct(
            400,
            'Request body can not be parsed to json.'
        );
    }
}
