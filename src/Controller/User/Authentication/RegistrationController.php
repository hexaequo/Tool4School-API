<?php


namespace App\Controller\User\Authentication;


use App\Controller\AbstractApiController;
use App\Messenger\JsonMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractApiController
{
    #[Route("/register", methods: ['POST'])]
    public function register(Request $request) {
        $data = $this->getJsonContent($request);

        $id = $this->generateRequestId('register');
        $message = new JsonMessage($id,$data);

        $envelope = new Envelope($message);
        $envelope = $envelope->with(... [new AmqpStamp('authentication')]);

        $this->dispatchMessage($envelope);

        return $this->generateAsyncResponse($id);
    }
}
