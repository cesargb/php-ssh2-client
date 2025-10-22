<?php

namespace Tests;

use Cesargb\Ssh\Ssh2Client;
use PHPUnit\Framework\TestCase;

class Ssh2Test extends TestCase
{
    public function test_get_finger_print()
    {
        $sshClient = Ssh2Client::connect(port: 2222);

        $this->assertTrue($sshClient->isConnected());

        $fingerPrint = $sshClient->fingerPrint();
        $this->assertNotEmpty($fingerPrint);

        $sshClient->disconnect();

        $this->assertFalse($sshClient->isConnected());
    }

    public function test_auth_with_password()
    {
        $sshClient = Ssh2Client::connect(port: 2222);

        $sshClient->withAuthPassword('root', 'root');

        $this->assertTrue($sshClient->isAuthenticated());

        $sshClient->disconnect();
    }

    public function test_scp_copy_authorized_keys()
    {
        $sshClient = Ssh2Client::connect(port: 2222)->withAuthPassword('root', 'root');

        $scpCommand = $sshClient->scp()->upload(__DIR__.'/fixtures/authorized_keys')
            ->to('/root/.ssh/authorized_keys');

        $this->assertTrue($scpCommand->success);
        $this->assertTrue($sshClient->exec('ls -la /root/.ssh/authorized_keys')->succeeded());

        $sshClient->disconnect();
    }

    public function test_auth_with_public_key()
    {
        $sshClient = Ssh2Client::connect(port: 2222)
            ->withAuthPublicKey('root', __DIR__.'/fixtures/openssh_new_ed25519.pub', __DIR__.'/fixtures/openssh_new_ed25519');

        $this->assertTrue($sshClient->isAuthenticated());

        $sshClient->disconnect();
    }
}
