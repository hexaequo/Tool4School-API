<?php


namespace App\Exception\Request;


use App\Exception\ApiException;
use Symfony\Component\HttpFoundation\Response;

class MissingHeaderException extends ApiException
{
    public function __construct($missingHeader)
    {
        parent::__construct(Response::HTTP_BAD_REQUEST, sprintf('"%s" header is missing.',$missingHeader));
    }
}
