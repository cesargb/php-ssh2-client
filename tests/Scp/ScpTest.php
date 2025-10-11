<?php

namespace Test\Scp;

use Cesargb\Ssh\Ssh2Client;
use PHPUnit\Framework\TestCase;

class ScpTest extends TestCase
{
    public function test_scp_send()
    {
        $tempFile = $this->generateTempFile('This is a test file for SCP upload.');
        $targetFile = '/tmp/'.basename($tempFile);

        $sshClient = new Ssh2Client()->connect(port: 2222)
            ->withAuthPassword('root', 'root');

        $sshClient->scp()->send($tempFile, $targetFile);

        unlink($tempFile);

        $this->assertEquals(
            'This is a test file for SCP upload.',
            $sshClient->exec('cat '.$targetFile)
        );

        $sshClient->disconnect();
    }

    // public function test_scp_receive()
    // {
    //     $sshClient = new Ssh2Client()->connect(port: 2222)
    //         ->withAuthPassword('root', 'root');

    //     $sshClient->scp()->receive('/root/.ssh/authorized_keys', __DIR__.'/fixtures/authorized_keys');

    //     $this->assertNotEmpty(file_get_contents(__DIR__.'/fixtures/authorized_keys'));

    //     $sshClient->disconnect();
    // }

    private function generateTempFile(string $content): string
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'scp_test');
        file_put_contents($tempFile, $content);

        return $tempFile;
    }
}
