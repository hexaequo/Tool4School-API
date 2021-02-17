<?php


namespace App\Tests\integration\MessageHandler;


use App\MessageHandler\ArrayMessageHandler;
use App\Messenger\ArrayMessage;
use App\Messenger\TestArrayMessageIn;
use App\Tests\integration\IntegrationTestCase;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class ArrayMessageHandlerTest extends IntegrationTestCase
{
    public function testMessageHandled() {
        $message = new TestArrayMessageIn('123',['test'=>true]);

        /** @var FilesystemAdapter $cache */
        $cache = self::$container->get('cache.app');

        $messageHandler = self::$container->get(ArrayMessageHandler::class);
        $messageHandler($message);

        $this->assertEquals($message,$cache->get('123', function() { return null; }));
    }

    public function testMessageHandledWithSameKeyBefore() {
        $message = new TestArrayMessageIn('123',['test'=>true]);
        $message2 = new TestArrayMessageIn('123',['aaa'=>'bbb']);

        /** @var FilesystemAdapter $cache */
        $cache = self::$container->get('cache.app');

        $messageHandler = self::$container->get(ArrayMessageHandler::class);
        $messageHandler($message);

        $this->assertEquals($message,$cache->get('123', function() { return null; }));

        $messageHandler($message2);

        $this->assertEquals($message2,$cache->get('123', function() { return null; }));
    }
}
