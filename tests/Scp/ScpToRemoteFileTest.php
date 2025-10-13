<?php

namespace Test\Scp;

use Cesargb\Ssh\Ssh2Client;
use PHPUnit\Framework\TestCase;

class ScpToRemoteFileTest extends TestCase
{
    public function test_scp_send_file_to_file()
    {
        $tempFile = $this->generateTempFile('This is a test file for SCP upload.');
        $targetFile = '/tmp/'.basename($tempFile);

        $sshClient = Ssh2Client::connect(port: 2222)->withAuthPassword('root', 'root');

        $copied = $sshClient->scpLocal($tempFile)->to($targetFile);

        unlink($tempFile);

        $this->assertTrue($copied);
        $this->assertEquals(
            'This is a test file for SCP upload.',
            $sshClient->exec('cat '.$targetFile)
        );

        $sshClient->disconnect();
    }

    public function test_scp_send_file_to_directory()
    {
        $tempFile = $this->generateTempFile('This is a test file for SCP upload.');
        $targetDir = '/tmp/';
        $targetFile = $targetDir.basename($tempFile);

        $sshClient = Ssh2Client::connect(port: 2222)
            ->withAuthPassword('root', 'root');

        $copied = $sshClient->scpLocal($tempFile)
            ->to($targetDir);

        unlink($tempFile);

        $this->assertTrue($copied);
        $this->assertEquals(
            'This is a test file for SCP upload.',
            $sshClient->exec('cat '.$targetFile)
        );

        $sshClient->disconnect();
    }

    private function generateTempFile(string $content): string
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'scp_test');
        file_put_contents($tempFile, $content);

        return $tempFile;
    }
}
