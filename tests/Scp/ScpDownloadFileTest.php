<?php

namespace Tests\Scp;

use Tests\SshCase;

class ScpDownloadFileTest extends SshCase
{
    protected function setUp(): void
    {
        self::$sshClient->exec('echo "file content" > /tmp/file.txt');
    }

    protected function tearDown(): void
    {
        self::$sshClient->exec('rm -f /tmp/file.txt');
    }

    public function test_scp_recv_file_to_file()
    {
        $localFileName = __DIR__.'/../fixtures/file.txt';
        $this->assertFileDoesNotExist($localFileName);

        $copied = self::$sshClient->scp()->download('/tmp/file.txt')->to($localFileName);

        $this->assertTrue($copied);
        $this->assertFileExists($localFileName);
        $this->assertEquals("file content\n", file_get_contents($localFileName));

        unlink($localFileName);
    }

    public function test_scp_send_recv_to_directory()
    {
        $localDir = __DIR__.'/../fixtures/';
        $this->assertFileDoesNotExist($localDir.'file.txt');

        $copied = self::$sshClient->scp()->download('/tmp/file.txt')->to($localDir);

        $this->assertTrue($copied);
        $this->assertFileExists($localDir.'file.txt');
        $this->assertEquals("file content\n", file_get_contents($localDir.'file.txt'));

        unlink($localDir.'file.txt');
    }
}
