<?php

namespace Tests\Exec;

use Tests\SshCase;

class Ssh2ExecTest extends SshCase
{
    public function test_ssh2_exec()
    {
        $result = self::$sshClient->exec(command: 'ls -la /');

        $this->assertEquals(0, $result->getExitStatus(), 'Exit status should be 0');
        $this->assertTrue($result->succeeded(), 'Command should succeed');
        $this->assertStringContainsString('bin', $result->output, 'Output should contain "bin"');
        $this->assertEmpty($result->errorOutput, 'Error output should be empty');
    }

    public function test_ssh2_exec_with_invalid_command()
    {
        $result = self::$sshClient->exec(command: 'invalid_command');

        $this->assertEquals(127, $result->getExitStatus(), 'Exit status should be 127 for command not found');
        $this->assertFalse($result->succeeded(), 'Command should not succeed');
        $this->assertStringContainsString('command not found', $result->errorOutput, 'Error output should contain "command not found"');
    }
}
