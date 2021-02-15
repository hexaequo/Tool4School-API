<?php


namespace App\Exception\Request;


use Symfony\Component\HttpKernel\Exception\HttpException;

class MissingDataException extends HttpException
{
    public function __construct(array $missingFields = [])
    {
        parent::__construct(
            422,
            json_encode([
                'message' => 'Fields are missing in the request.',
                'fields' => $missingFields
            ])
        );
    }
}
