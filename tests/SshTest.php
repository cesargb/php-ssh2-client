<?php

namespace Test;

use Cesargb\Ssh\SshClient;
use PHPUnit\Framework\TestCase;

class SshTest extends TestCase
{
    public function test_get_finger_print()
    {
        $ssh = new SshClient('localhost');

        $connection = $ssh->connect();

        $this->assertTrue($connection->isConnected());

        $fingerPrint = $connection->fingerPrint();
        $this->assertNotEmpty($fingerPrint);

        $connection->disconnect();

        $this->assertFalse($connection->isConnected());

    }

    public function test_auth_with_key()
    {
        $ssh = new SshClient('localhost');

        $connection = $ssh->connect();

        $connection->withAuthPublicKey('admin', __DIR__.'/id_rsa.pub', __DIR__.'/id_rsa');

        $connection->disconnect();

        $this->assertFalse($connection->isConnected());

    }
}
