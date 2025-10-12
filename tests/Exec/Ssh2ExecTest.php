<?php

namespace Test\Scp;

use Cesargb\Ssh\Ssh2Client;
use PHPUnit\Framework\TestCase;

class Ssh2ExecTest extends TestCase
{
    public function test_ssh2_exec()
    {
        $sshClient = Ssh2Client::connect(port: 2222)
            ->withAuthPassword(username: 'root', password: 'root');

        $result = $sshClient->exec(command: 'ls -la /');

        $sshClient->disconnect();

        $this->assertEquals(0, $result->getExitStatus(), 'Exit status should be 0');
        $this->assertTrue($result->succeeded(), 'Command should succeed');
        $this->assertStringContainsString('bin', $result->output, 'Output should contain "bin"');
        $this->assertEmpty($result->errorOutput, 'Error output should be empty');
    }

    public function test_ssh2_exec_with_invalid_command()
    {
        $sshClient = Ssh2Client::connect(port: 2222)
            ->withAuthPassword(username: 'root', password: 'root');

        $result = $sshClient->exec(command: 'invalid_command');

        $sshClient->disconnect();

        $this->assertEquals(127, $result->getExitStatus(), 'Exit status should be 127 for command not found');
        $this->assertFalse($result->succeeded(), 'Command should not succeed');
        $this->assertStringContainsString('command not found', $result->errorOutput, 'Error output should contain "command not found"');
    }
}
