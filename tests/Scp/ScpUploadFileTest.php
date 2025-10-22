<?php

namespace Tests\Scp;

use Tests\SshCase;

class ScpUploadFileTest extends SshCase
{
    public function test_scp_send_file_to_file()
    {
        $tempFile = $this->generateTempFile('This is a test file for SCP upload.');
        $targetFile = '/tmp/'.basename($tempFile);

        $copied = self::$sshClient->scp()->upload($tempFile)->to($targetFile);

        unlink($tempFile);

        $this->assertTrue($copied);
        $this->assertEquals(
            'This is a test file for SCP upload.',
            self::$sshClient->exec('cat '.$targetFile)
        );
    }

    public function test_scp_send_file_to_directory()
    {
        $tempFile = $this->generateTempFile('This is a test file for SCP upload.');
        $targetDir = '/tmp/';
        $targetFile = $targetDir.basename($tempFile);

        $copied = self::$sshClient->scp()->upload($tempFile)->to($targetDir);

        unlink($tempFile);

        $this->assertTrue($copied);
        $this->assertEquals(
            'This is a test file for SCP upload.',
            self::$sshClient->exec('cat '.$targetFile)
        );
    }

    private function generateTempFile(string $content): string
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'scp_test');
        file_put_contents($tempFile, $content);

        return $tempFile;
    }
}
