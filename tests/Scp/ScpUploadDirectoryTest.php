<?php

namespace Tests\Scp;

use Tests\SshCase;

class ScpUploadDirectoryTest extends SshCase
{
    private string $remoteWorkDir = '/tmp/workDir';

    protected function setUp(): void
    {
        self::$sshSession->command()->execute('mkdir '.$this->remoteWorkDir);
    }

    protected function tearDown(): void
    {
        self::$sshSession->command()->execute('rm -rf '.$this->remoteWorkDir);
    }

    public function test_scp_send_directory_to_directory()
    {
        $localDir = __DIR__.'/../fixtures/dirs';
        $targetDir = $this->remoteWorkDir;

        $copied = self::$sshSession->scp()->upload($localDir)
            ->recursive()
            ->to($targetDir);

        $this->assertTrue($copied->succeeded(), 'Recursive copy failed');
        $this->assertTrue(self::$sshSession->command()->execute('test -d '.$this->remoteWorkDir.'/dirs')->succeeded(), 'Directory was not copied');
        $this->assertTrue(self::$sshSession->command()->execute('test -d '.$this->remoteWorkDir.'/dirs/subdir')->succeeded(), 'Subdirectory was not copied');
        $this->assertEquals('file1', trim(self::$sshSession->command()->execute('cat '.$this->remoteWorkDir.'/dirs/file1.txt')));
        $this->assertEquals('file2', trim(self::$sshSession->command()->execute('cat '.$this->remoteWorkDir.'/dirs/file2.txt')));
        $this->assertEquals('file3', trim(self::$sshSession->command()->execute('cat '.$this->remoteWorkDir.'/dirs/subdir/file3.txt')));
    }

    public function test_scp_send_directory_entries_to_directory()
    {
        $localDir = __DIR__.'/../fixtures/dirs/';
        $targetDir = $this->remoteWorkDir;

        $copied = self::$sshSession->scp()->upload($localDir)
            ->recursive()
            ->to($targetDir);

        $this->assertTrue($copied->succeeded(), 'Recursive copy failed');
        $this->assertEquals('file1', trim(self::$sshSession->command()->execute('cat '.$this->remoteWorkDir.'/file1.txt')));
        $this->assertEquals('file2', trim(self::$sshSession->command()->execute('cat '.$this->remoteWorkDir.'/file2.txt')));
        $this->assertTrue(self::$sshSession->command()->execute('test -d '.$this->remoteWorkDir.'/subdir')->succeeded(), 'Subdirectory was not copied');
        $this->assertEquals('file3', trim(self::$sshSession->command()->execute('cat '.$this->remoteWorkDir.'/subdir/file3.txt')));
    }
}
