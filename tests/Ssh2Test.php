<?php

namespace Test;

use Cesargb\Ssh\Ssh2Client;
use PHPUnit\Framework\TestCase;

class Ssh2Test extends TestCase
{
    public function test_get_finger_print()
    {
        $sshClient = new Ssh2Client()->connect(port: 2222);

        $this->assertTrue($sshClient->isConnected());

        $fingerPrint = $sshClient->fingerPrint();
        $this->assertNotEmpty($fingerPrint);

        $sshClient->disconnect();

        $this->assertFalse($sshClient->isConnected());
    }

    public function test_auth_with_password()
    {
        $sshClient = new Ssh2Client()->connect(port: 2222);

        $sshClient->withAuthPassword('root', 'root');

        $this->assertTrue($sshClient->isAuthenticated());

        $sshClient->disconnect();
    }

    public function test_scp_copy_authorized_keys()
    {
        $sshClient = new Ssh2Client()
            ->connect(port: 2222)
            ->withAuthPassword('root', 'root');

        $sshClient->scp()->send(__DIR__.'/fixtures/authorized_keys', '/root/.ssh/authorized_keys');

        $this->assertNotEmpty($sshClient->exec('ls -la /root/.ssh/authorized_keys')->output);

        $sshClient->disconnect();
    }

    public function test_auth_with_public_key()
    {
        $sshClient = new Ssh2Client()->connect(port: 2222)
            ->withAuthPublicKey('root', __DIR__.'/fixtures/openssh_new_ed25519.pub', __DIR__.'/fixtures/openssh_new_ed25519');

        $this->assertTrue($sshClient->isAuthenticated());

        $sshClient->disconnect();
    }
}
