<?php


namespace App\Tests\functional\Controller\User\Authentication;

use App\Messenger\ArrayMessage;
use App\Tests\functional\FunctionalTestCase;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpTransport;
use Symfony\Component\Messenger\Transport\InMemoryTransport;

class RegistrationControllerTest extends FunctionalTestCase
{
    public function testValidData() {
        $this->client->request('POST', '/register',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode([
                'username' => 'testUsername',
                'password' => 'testPassword'
            ])
        );

        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent(),true);

        $this->assertEquals(202,$response->getStatusCode());
        $this->assertArrayHasKey('id',$responseData);
        $this->assertArrayHasKey('href',$responseData);
        $this->assertEquals('/job/'.$responseData['id'],$responseData['href']);

        $this->assertCount(1,self::$container->get('messenger.transport.main')->get());
    }

    public function testBadMethod() {
        $this->client->request('PATCH', '/register',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: '{"username": "testusername", "password": "testpassword"}'
        );

        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent(),true);

        $this->assertEquals(405,$response->getStatusCode());
        $this->assertEquals('No route found for "PATCH /register": Method Not Allowed (Allow: POST)',$responseData['error']);
    }

    public function testBadHeader() {
        $this->client->request('POST', '/register',
            content: '{"username": "testusername" "password": "testpassword"}'
        );

        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent(),true);

        $this->assertEquals(415,$response->getStatusCode());
        $this->assertEquals('Content-Type header must be application/json',$responseData['error']);
    }

    public function testNotValidJson() {
        $this->client->request('POST', '/register',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: '{"username": "testusername" "password": "testpassword"}'
        );

        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent(),true);

        $this->assertEquals(400,$response->getStatusCode());
        $this->assertEquals('Request body can not be parsed to json.',$responseData['error']);
    }
}
