<?php


namespace App\EventListener;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event) {
        $exception = $event->getThrowable();
        $message = $exception->getMessage();

        $code = Response::HTTP_INTERNAL_SERVER_ERROR;
        if ($exception instanceof HttpExceptionInterface) {
            $code = $exception->getStatusCode();
        }

        $responseMessage = $message;
        if(json_decode($message)) {
            $responseMessage = json_decode($message);
        }

        $response = new JsonResponse([
            'error' => $responseMessage
        ],$code);

        $event->setResponse($response);
    }
}