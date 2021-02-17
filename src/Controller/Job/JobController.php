<?php


namespace App\Controller\Job;


use App\Controller\AbstractApiController;
use App\Exception\ApiException;
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

                if(substr($statusCode,0,1) !== '2' and isset($jobResponse->getData()['error'])) {
                    throw new ApiException($statusCode,json_encode($jobResponse->getData()['error']));
                }

                if(isset($jobResponse->getData()['Content-Location'])) {
                    return new Response(null,$statusCode,[
                        'Content-Location' => $jobResponse->getData()['Content-Location']
                    ]);
                }

                $data = $jobResponse->getData();
                if(isset($data['code'])) unset($data['code']);

                return new JsonResponse($data,$statusCode);
            }
            else {
                return new JsonResponse(['error'=>'Unknown'],500);
            }
        }
        return new Response(null,202);
    }
}
