<?php

namespace Tests\Files;

use Cesargb\Ssh\Files\Path;
use Cesargb\Ssh\Ssh2Client;
use Tests\SshCase;

class PathRemoteTest extends SshCase
{
    public function test_path_remote_does_not_exist()
    {
        $filename = '/etc/non_existent_file';
        $sshClient = Ssh2Client::connect(port: 2222)->withAuthPassword('root', 'root');

        $path = (new Path($filename))->asRemote($sshClient);

        $this->assertFalse($path->exists(), 'Path should not exist');

        $sshClient->disconnect();
    }

    public function test_path_remote_is_file()
    {
        $filename = '/etc/passwd';
        $sshClient = Ssh2Client::connect(port: 2222)->withAuthPassword('root', 'root');

        $path = (new Path($filename))->asRemote($sshClient);

        $this->assertTrue($path->exists(), 'Path should exist');
        $this->assertTrue($path->isFile(), 'Path should be a file');
        $this->assertFalse($path->isDir(), 'Path should not be a directory');

        $sshClient->disconnect();
    }

    public function test_path_remote_is_directory()
    {
        $filename = '/etc';
        $sshClient = Ssh2Client::connect(port: 2222)->withAuthPassword('root', 'root');

        $path = (new Path($filename))->asRemote($sshClient);

        $this->assertTrue($path->exists(), 'Path should exist');
        $this->assertFalse($path->isFile(), 'Path should be a file');
        $this->assertTrue($path->isDir(), 'Path should not be a directory');

        $sshClient->disconnect();
    }
}
