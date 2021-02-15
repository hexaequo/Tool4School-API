<?php


namespace App\Controller;


use Symfony\Component\HttpFoundation\Request;

abstract class AbstractApiController
{
    public function checkContentType(Request $request, $contentType = 'application/json') {
        if($request->headers->get('Content-Type') != $contentType) {
            //TODO: throw an exception
        }
    }

    public function getJsonContent(Request $request) {
        $this->checkContentType($request);

        $body = $request->getContent();
        $data = json_decode($body,true);

        if(!$data) {
            //TODO: throw an exception
        }

        return $data;
    }

    public function checkFields(array $data, array $requiredFields) {
        foreach ($requiredFields as $key => $field) {
            if(isset($data[$field])) {
                unset($requiredFields[$key]);
            }
        }

        $missingFields = [];
        foreach ($requiredFields as $field) {
            $missingFields[] = $field;
        }

        if(!empty($missingFields)) //TODO: Throw a custom exception
            throw new \Exception();
    }
}
