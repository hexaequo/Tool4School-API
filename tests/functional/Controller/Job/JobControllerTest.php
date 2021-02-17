<?php


namespace App\Tests\functional\Controller\Job;


use App\Messenger\ArrayMessage;
use App\Tests\functional\FunctionalTestCase;

class JobControllerTest extends FunctionalTestCase
{
    public function testNotReceived() {
        $this->client->request('GET', '/job/test');

        $response = $this->client->getResponse();

        $this->assertEquals(202,$response->getStatusCode());
    }

    public function testError() {
        $cache = $this->getCache();
        $id = uniqid('testError_');
        $cache->get($id,function() use ($id) {
            return new ArrayMessage($id,[
                'code' => 400,
                'error' => 'Test error'
            ]);
        });

        $this->client->request('GET','/job/'.$id);

        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent(),true);

        $this->assertEquals(400,$response->getStatusCode());
        $this->assertEquals([
            'error' => 'Test error'
        ], $responseData);
    }

    public function testArrayError() {
        $cache = $this->getCache();
        $id = uniqid('testError_');
        $cache->get($id,function() use ($id) {
            return new ArrayMessage($id,[
                'code' => 401,
                'error' => [
                    'title' => 'Test error',
                    'values' => [1,2,3]
                ]
            ]);
        });

        $this->client->request('GET','/job/'.$id);

        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent(),true);

        $this->assertEquals(401,$response->getStatusCode());
        $this->assertEquals([
            'error' => [
                'title' => 'Test error',
                'values' => [1,2,3]
            ]
        ], $responseData);
    }

    public function testContentLocation() {
        $cache = $this->getCache();
        $id = uniqid('testContentLocation_');
        $cache->get($id,function() use ($id) {
            return new ArrayMessage($id,[
                'code' => 201,
                'Content-Location' => '/test'
            ]);
        });

        $this->client->request('GET','/job/'.$id);

        $response = $this->client->getResponse();

        $this->assertEquals(201,$response->getStatusCode());
        $this->assertEquals('/test', $response->headers->get('Content-Location'));
    }

    public function testNormalResponse() {
        $cache = $this->getCache();
        $id = uniqid('testContentLocation_');
        $cache->get($id,function() use ($id) {
            return new ArrayMessage($id,[
                'code' => 200,
                'data' => [1,2,3]
            ]);
        });

        $this->client->request('GET','/job/'.$id);

        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent(),true);

        $this->assertEquals(200,$response->getStatusCode());
        $this->assertEquals(['data'=>[1,2,3]],$responseData);
    }

    public function testBadResponseFromService() {
        $cache = $this->getCache();
        $id = uniqid('testContentLocation_');
        $cache->get($id,function() use ($id) {
            return 'test';
        });

        $this->client->request('GET','/job/'.$id);

        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent(),true);

        $this->assertEquals(500,$response->getStatusCode());
        $this->assertEquals(['error'=>'Unknown'],$responseData);
    }
}
