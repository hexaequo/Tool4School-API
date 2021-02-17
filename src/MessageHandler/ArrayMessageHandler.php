<?php


namespace App\MessageHandler;


use App\Messenger\ArrayMessage;
use Psr\Container\ContainerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Contracts\Cache\CacheInterface;

class ArrayMessageHandler implements MessageHandlerInterface
{
    public function __construct(private CacheInterface $cache){}

    public function __invoke(ArrayMessage $message)
    {
        $this->cache->delete($message->getId());
        $this->cache->get($message->getId(),function() use ($message){
            return $message;
        });
    }
}
