<?php
namespace App\Tests;

trait RoleUser {

    public function setUp()
    {
        //parent::setUp();

        self::bootKernel();
        $container = self::$kernel->getContainer();
        $container = self::$container;
        $cache = self::$container->get('App\Utils\Interfaces\CacheInterface');
        $this->cache = $cache->cache;
        $this->cache->clear();

        $this->client = static::createClient([], [
            'PHP_AUTH_USER' => 'jd@symf4.com',
            'PHP_AUTH_PW' => 'passw',
        ]);
        // $this->client->disableReboot();

        $this->em = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        // $this->em->beginTransaction();
        // $this->em->getConnection()->setAutoCommit(false);
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->cache->clear();
        // $this->em->rollback();
        $this->em->close();
        $this->em = null; // avoid memory leaks
    }
}
