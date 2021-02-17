<?php


namespace App\Controller\Job;


use App\Controller\AbstractApiController;
use App\Messenger\ArrayMessage;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;

class JobController extends AbstractApiController
{
    #[Route("/job/{id}", methods: ['GET'])]
    public function getJob(string $id, CacheInterface $cache) {
        $jobResponse = $cache->get($id,function() { return null; });
        if($jobResponse) {
            if($jobResponse instanceof ArrayMessage){
                $statusCode = $jobResponse->getData()['code'] ?? 200;

                return new JsonResponse($jobResponse->getData(),$statusCode);
            }
            else {
                return new JsonResponse(['error'=>'Unknown',500]);
            }
        }
        return new Response(null,202);
    }
}
