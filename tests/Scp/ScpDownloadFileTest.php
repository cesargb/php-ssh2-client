<?php

namespace Tests\Scp;

use Tests\SshCase;

class ScpDownloadFileTest extends SshCase
{
    protected function setUp(): void
    {
        self::$sshSession->command()->execute('echo "file content" > /tmp/file.txt');
    }

    protected function tearDown(): void
    {
        self::$sshSession->command()->execute('rm -f /tmp/file.txt');
    }

    public function test_scp_recv_file_to_file()
    {
        $localFileName = __DIR__.'/../fixtures/file.txt';
        $this->assertFileDoesNotExist($localFileName);

        $copied = self::$sshSession->scp()->download('/tmp/file.txt')->to($localFileName);

        $this->assertTrue($copied->succeeded());
        $this->assertFileExists($localFileName);
        $this->assertEquals("file content\n", file_get_contents($localFileName));

        unlink($localFileName);
    }

    public function test_scp_send_recv_to_directory()
    {
        $localDir = __DIR__.'/../fixtures/';
        $this->assertFileDoesNotExist($localDir.'file.txt');

        $copied = self::$sshSession->scp()->download('/tmp/file.txt')->to($localDir);

        $this->assertTrue($copied->succeeded());
        $this->assertFileExists($localDir.'file.txt');
        $this->assertEquals("file content\n", file_get_contents($localDir.'file.txt'));

        unlink($localDir.'file.txt');
    }
}
