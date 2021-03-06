<?php


namespace App\Tests\functional;


use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Contracts\Cache\CacheInterface;

abstract class FunctionalTestCase extends WebTestCase
{
    protected KernelBrowser $client;
    private EntityManagerInterface|null $entityManager = null;
    private CacheInterface|null $cache = null;

    public function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();

        $this->entityManager = self::$container->get('doctrine.orm.entity_manager');

        $purger = new ORMPurger($this->entityManager);
        $purger->purge();
    }

    public function getEntityManager() {
        return $this->entityManager;
    }

    public function getCache() {
        if(!$this->cache) {
            $this->cache = self::$container->get(CacheInterface::class);
        }
        return $this->cache;

    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}
