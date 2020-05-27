<?php
namespace App\Tests;

trait RoleAdmin {

    public function setUp()
    {
        $this->client = static::createClient([], [
            'PHP_AUTH_USER' => 'jw@symf4.com',
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
        // $this->em->rollback();
        $this->em->close();
        $this->em = null; // avoid memory leaks
    }
}
