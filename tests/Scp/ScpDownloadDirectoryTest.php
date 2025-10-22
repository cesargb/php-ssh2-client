<?php

namespace Tests\Scp;

use Tests\SshCase;

class ScpDownloadDirectoryTest extends SshCase
{
    protected function setUp(): void
    {
        mkdir(__DIR__.'/../fixtures/tmp');
        self::$sshClient->exec('mkdir /tmp/workdir');
        self::$sshClient->exec('echo "file content" > /tmp/workdir/file1.txt');
        self::$sshClient->exec('echo "another file" > /tmp/workdir/file2.txt');
        self::$sshClient->exec('mkdir /tmp/workdir/subdir');
        self::$sshClient->exec('echo "subdir file" > /tmp/workdir/subdir/file3.txt');
    }

    protected function tearDown(): void
    {
        self::$sshClient->exec('rm -rf /tmp/workdir');
        // rmdir(__DIR__.'/../fixtures/tmp');
    }

    public function test_scp_recv_directory()
    {
        self::$sshClient->scp()->download('/tmp/workdir')
            ->recursive()
            ->to(__DIR__.'/../fixtures/tmp');
    }
}
