<?php

namespace Test;

use Cesargb\Ssh\Client;
use PHPUnit\Framework\TestCase;

class SshTest extends TestCase
{
    public function test_get_finger_print()
    {
        $ssh = new Client('loclahost', 'cesargb.dev');

        $connection = $ssh->connect();

        $this->assertTrue($connection->isConnected());

        $fingerPrint = $connection->fingerPrint();
        $this->assertNotEmpty($fingerPrint);

        $connection->disconnect();

        $this->assertFalse($connection->isConnected());

    }


}
