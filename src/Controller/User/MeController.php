<?php


namespace App\Controller\User;


use App\Controller\AbstractApiController;
use App\Messenger\ArrayMessage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Routing\Annotation\Route;

class MeController extends AbstractApiController
{
    #[Route("/me", methods: ["GET"])]
    public function me(Request $request) {
        $token = $this->getBearerToken($request);
        $data = [
            'action' => 'me',
            'Bearer' => $token
        ];

        $id = $this->generateRequestId('me');
        $message = new ArrayMessage($id,$data);

        $stamps = [new AmqpStamp('authentication')];

        $this->dispatchCachedMessage($message,$stamps);

        return $this->generateAsyncResponse($id);
    }
}
