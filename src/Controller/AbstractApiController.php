<?php


namespace App\Controller;


use App\Exception\Request\JsonNotParsableException;
use App\Exception\Request\MissingDataException;
use App\Exception\Request\WrongContentTypeException;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractApiController
{
    public function checkContentType(Request $request, $contentType = 'application/json') {
        if($request->headers->get('Content-Type') != $contentType) {
            throw new WrongContentTypeException($contentType);
        }
    }

    public function getJsonContent(Request $request) {
        $this->checkContentType($request);

        $body = $request->getContent();
        $data = json_decode($body,true);

        if(!$data) {
            throw new JsonNotParsableException();
        }

        return $data;
    }

    public function generateRequestId($type = null) {
        if($type) return uniqid($type.'_');
        return uniqid();
    }
}
