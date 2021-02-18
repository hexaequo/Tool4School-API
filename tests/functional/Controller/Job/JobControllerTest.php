<?php


namespace App\Tests\functional\Controller\Job;


use App\Messenger\ArrayMessage;
use App\Tests\functional\FunctionalTestCase;

class JobControllerTest extends FunctionalTestCase
{
    public function testUnknwonMessage() {
        $this->client->request('GET', '/job/test');

        $response = $this->client->getResponse();

        $this->assertEquals(404,$response->getStatusCode());
    }

    public function testNotFinished() {
        $cache = $this->getCache();
        $id = uniqid('testError_');
        /** @var ArrayMessage $message */
        $message = $cache->get($id,function() use ($id) {
            return ArrayMessage::createMessage($id,[
                'code' => 400,
                'error' => 'Test error'
            ]);
        });

        $this->client->request('GET','/job/'.$id);

        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent(),true);

        $this->assertEquals(202,$response->getStatusCode());
        $this->assertEquals([
            'sentAt' => $message->getSentAt()->format('Y-m-d H:i:s.v'),
            'id' => $id,
            'data' => [],
            'startedAt' => null,
            'endedAt' => null,
            'receivedAt' => null,
            'ended' => false
        ], $responseData);
    }

    public function testError() {
        $cache = $this->getCache();
        $id = uniqid('testError_');
        $cache->get($id,function() use ($id) {
            $message = ArrayMessage::createMessage($id,[
                'code' => 400,
                'error' => 'Test error'
            ]);
            $message->setReceivedAt(new \DateTime());
            return $message;
        });

        $this->client->request('GET','/job/'.$id);

        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent(),true);

        $this->assertEquals(400,$response->getStatusCode());
        $this->assertEquals([
            'error' => 'Test error'
        ], $responseData['data']);
    }

    public function testArrayError() {
        $cache = $this->getCache();
        $id = uniqid('testError_');
        $cache->get($id,function() use ($id) {
            $message = ArrayMessage::createMessage($id,[
                'code' => 401,
                'error' => [
                    'title' => 'Test error',
                    'values' => [1,2,3]
                ]
            ]);
            $message->setReceivedAt(new \DateTime());
            return $message;
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
        ], $responseData['data']);
    }

    public function testContentLocation() {
        $cache = $this->getCache();
        $id = uniqid('testContentLocation_');
        $cache->get($id,function() use ($id) {
            $message = ArrayMessage::createMessage($id,[
                'code' => 201,
                'Content-Location' => '/test'
            ]);
            $message->setReceivedAt(new \DateTime());
            return $message;
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
            $message = ArrayMessage::createMessage($id,[
                'code' => 200,
                'data' => [1,2,3]
            ]);
            $message->setReceivedAt(new \DateTime());
            return $message;
        });

        $this->client->request('GET','/job/'.$id);

        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent(),true);

        $this->assertEquals(200,$response->getStatusCode());
        $this->assertEquals(['data'=>[1,2,3]],$responseData['data']);
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
