<?php


namespace App\Controller\User\Authentication;


use App\Controller\AbstractApiController;
use App\Messenger\ArrayMessage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Routing\Annotation\Route;

class AuthenticationController extends AbstractApiController
{
    #[Route("/login", methods: ["POST"])]
    public function login(Request $request) {
        $data = $this->getJsonContent($request);
        $data = array_merge($data,['action' => 'register']);

        $id = $this->generateRequestId('jwt_get');
        $message = new ArrayMessage($id,$data);

        $stamps = [new AmqpStamp('authentication')];

        $this->dispatchCachedMessage($message,$stamps);

        return $this->generateAsyncResponse($id);
    }
}
