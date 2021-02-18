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

        /** @var ArrayMessage $received */
        $received = $cache->get('123', function() { return null; });
        $this->assertEquals($message->getId(),$received->getId());
        $this->assertEquals($message->getData(),$received->getData());
    }

    public function testMessageHandledWithSameKeyBefore() {
        $message = new TestArrayMessageIn('123',['test'=>true]);
        $message2 = new TestArrayMessageIn('123',['aaa'=>'bbb']);

        /** @var FilesystemAdapter $cache */
        $cache = self::$container->get('cache.app');

        $messageHandler = self::$container->get(ArrayMessageHandler::class);
        $messageHandler($message);

        /** @var ArrayMessage $received */
        $received = $cache->get('123', function() { return null; });
        $this->assertEquals($message->getId(),$received->getId());
        $this->assertEquals($message->getData(),$received->getData());

        $messageHandler($message2);

        /** @var ArrayMessage $received */
        $received = $cache->get('123', function() { return null; });
        $this->assertEquals($message2->getId(),$received->getId());
        $this->assertEquals($message2->getData(),$received->getData());
    }
}
