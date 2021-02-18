<?php


namespace App\Controller\Job;


use App\Controller\AbstractApiController;
use App\Exception\ApiException;
use App\Messenger\ArrayMessage;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\CacheInterface;

class JobController extends AbstractApiController
{
    #[Route("/job/{id}", methods: ['GET'])]
    public function getJob(string $id, CacheInterface $cache, SerializerInterface $serializer) {
        $jobResponse = $cache->get($id,function() { return null; });
        /** @var ArrayMessage|null $authenticationJobResponse */
        $authenticationJobResponse = $cache->get($id.'_authentication',function() { return null; });
        if($jobResponse) {
            if($jobResponse instanceof ArrayMessage){
                $headers = ['Content-Type'=>'application/json'];
                $statusCode = Response::HTTP_OK;
                if($jobResponse->isEnded() and ($authenticationJobResponse === null or $authenticationJobResponse->isEnded())) {
                    $data = $jobResponse->getData();
                    if($authenticationJobResponse and $authenticationJobResponse->getData()['code'] === Response::HTTP_UNAUTHORIZED)
                        $data = $authenticationJobResponse->getData();

                    if(isset($data['code'])) {
                        $statusCode = $data['code'];
                        unset($data['code']);
                    }

                    if(isset($data['Content-Location'])) {
                        $headers['Content-Location'] = $data['Content-Location'];
                        unset($data['Content-Location']);
                    }

                    $jobResponse->setData($data);
                }
                else {
                    $statusCode = Response::HTTP_ACCEPTED;
                    $jobResponse->setData([]);
                }

                return new Response(
                    $serializer->serialize($jobResponse,'json'),
                    $statusCode,
                    $headers
                );
            }
            else {
                return new JsonResponse(['error'=>'Unknown'],Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
        return new Response(null,Response::HTTP_NOT_FOUND);
    }
}
