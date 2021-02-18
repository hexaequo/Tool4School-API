<?php


namespace App\Exception\Request;


use App\Exception\ApiException;
use Symfony\Component\HttpFoundation\Response;

class MalformedHeaderException extends ApiException
{
    public function __construct($headerName, $format)
    {
        parent::__construct(Response::HTTP_BAD_REQUEST, [
            'title' => sprintf('Header "%s" is malformed.',$headerName),
            'format' => $format
        ]);
    }
}
