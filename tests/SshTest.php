<?php

namespace Test;

use Cesargb\Ssh\SshClient;
use PHPUnit\Framework\TestCase;

class SshTest extends TestCase
{
    public function test_get_finger_print()
    {
        $ssh = new SshClient('localhost')->withPort(2222);

        $connection = $ssh->connect();

        $this->assertTrue($connection->isConnected());

        $fingerPrint = $connection->fingerPrint();
        $this->assertNotEmpty($fingerPrint);

        $connection->disconnect();

        $this->assertFalse($connection->isConnected());
    }

    public function test_auth_with_auth()
    {
        $ssh = new SshClient('localhost')->withPort(2222);

        $connection = $ssh->connect();

        $connection->withAuthPassword('root', 'secret123');

        $this->assertTrue($connection->isAuthenticated());

        $connection->disconnect();
    }
}
