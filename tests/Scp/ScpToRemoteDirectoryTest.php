<?php

namespace Test\Scp;

use Cesargb\Ssh\Ssh2Client;
use PHPUnit\Framework\TestCase;

class ScpToRemoteDirectoryTest extends TestCase
{
    private static Ssh2Client $sshClient;

    private string $workDir = '/tmp/workdir';

    public static function setUpBeforeClass(): void
    {
        self::$sshClient = Ssh2Client::connect(port: 2222)
            ->withAuthPassword('root', 'root');
    }

    public static function tearDownAfterClass(): void
    {
        self::$sshClient->disconnect();
    }

    protected function setUp(): void
    {
        self::$sshClient->exec('mkdir '.$this->workDir);
    }

    protected function tearDown(): void
    {
        self::$sshClient->exec('rm -rf '.$this->workDir);
    }

    public function test_scp_send_directory_to_directory()
    {
        $localDir = __DIR__.'/../fixtures/dirs';
        $targetDir = $this->workDir;

        $copied = self::$sshClient->scpLocal($localDir)
            ->recursive()
            ->to($targetDir);

        $this->assertTrue($copied, 'Recursive copy failed');
        $this->assertTrue(self::$sshClient->exec('test -d '.$this->workDir.'/dirs')->succeeded(), 'Directory was not copied');
        $this->assertTrue(self::$sshClient->exec('test -d '.$this->workDir.'/dirs/subdir')->succeeded(), 'Subdirectory was not copied');
        $this->assertEquals('file1', trim(self::$sshClient->exec('cat '.$this->workDir.'/dirs/file1.txt')));
        $this->assertEquals('file2', trim(self::$sshClient->exec('cat '.$this->workDir.'/dirs/file2.txt')));
        $this->assertEquals('file3', trim(self::$sshClient->exec('cat '.$this->workDir.'/dirs/subdir/file3.txt')));
    }

    public function test_scp_send_directory_entries_to_directory()
    {
        $localDir = __DIR__.'/../fixtures/dirs/';
        $targetDir = $this->workDir;

        $copied = self::$sshClient->scpLocal($localDir)
            ->recursive()
            ->to($targetDir);

        $this->assertTrue($copied, 'Recursive copy failed');
        $this->assertEquals('file1', trim(self::$sshClient->exec('cat '.$this->workDir.'/file1.txt')));
        $this->assertEquals('file2', trim(self::$sshClient->exec('cat '.$this->workDir.'/file2.txt')));
        $this->assertTrue(self::$sshClient->exec('test -d '.$this->workDir.'/subdir')->succeeded(), 'Subdirectory was not copied');
        $this->assertEquals('file3', trim(self::$sshClient->exec('cat '.$this->workDir.'/subdir/file3.txt')));
    }
}
