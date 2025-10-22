<?php

namespace Tests\Exec;

use Cesargb\Ssh\Exceptions\SshCommandException;
use Tests\SshCase;

class Ssh2ExecTest extends SshCase
{
    public function test_ssh2_exec()
    {
        $result = self::$sshSession->command()->execute('ls -la /');

        $this->assertEquals(0, $result->getExitStatus(), 'Exit status should be 0');
        $this->assertTrue($result->succeeded(), 'Command should succeed');
        $this->assertStringContainsString('bin', $result->output, 'Output should contain "bin"');
        $this->assertEmpty($result->errorOutput, 'Error output should be empty');
    }

    public function test_ssh2_exec_with_invalid_command()
    {
        $result = self::$sshSession->command()->execute('invalid_command');

        $this->assertEquals(127, $result->getExitStatus(), 'Exit status should be 127 for command not found');
        $this->assertFalse($result->succeeded(), 'Command should not succeed');
        $this->assertStringContainsString('command not found', $result->errorOutput, 'Error output should contain "command not found"');
    }

    public function test_ssh2_exec_with_thrown()
    {
        try {
            self::$sshSession->throw()->command()->execute('command_does_not_exist');
        } catch (SshCommandException $e) {
            $this->assertEquals(127, $e->result->getExitStatus(), 'Exit status should be 127 for command not found');
            $this->assertFalse($e->result->succeeded(), 'Command should not succeed');
            $this->assertStringContainsString('command not found', $e->result->errorOutput, 'Error output should contain "command not found"');
        }
    }
}
