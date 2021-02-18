<?php


namespace App\Exception\Request;


use App\Exception\ApiException;

class MissingHeaderException extends ApiException
{
    public function __construct($missingHeader)
    {
        parent::__construct(400, sprintf('"%s" header is missing.',$missingHeader));
    }
}
