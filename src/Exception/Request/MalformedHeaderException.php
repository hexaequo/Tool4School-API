<?php


namespace App\Exception\Request;


use App\Exception\ApiException;

class MalformedHeaderException extends ApiException
{
    public function __construct($headerName, $format)
    {
        parent::__construct(400, [
            'title' => sprintf('Header "%s" is malformed.',$headerName),
            'format' => $format
        ]);
    }
}
