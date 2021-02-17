<?php


namespace App\Controller;


use App\Exception\Request\JsonNotParsableException;
use App\Exception\Request\WrongContentTypeException;
use App\Messenger\ArrayMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Envelope;
use Symfony\Contracts\Cache\CacheInterface;

abstract class AbstractApiController extends AbstractController
{
    public function __construct(private CacheInterface $cache)
    {
    }

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
        $id = uniqid();
        if($type) $id = uniqid($type.'_');
        return $id;
    }

    public function generateAsyncResponse(string $id) {
        return new JsonResponse([
            'id' => $id,
            'href' => '/job/'.$id
        ],202);
    }

    public function dispatchCachedMessage(ArrayMessage $message, array $stamps = []): Envelope
    {
        $this->cache->delete($message->getId());

        $this->cache->get($message->getId(),function() use ($message) {
            return $message;
        });

        return parent::dispatchMessage($message, $stamps);
    }
}
