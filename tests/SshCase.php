<?php

namespace Tests;

use Cesargb\Ssh\Ssh2Client;
use PHPUnit\Framework\TestCase;

abstract class SshCase extends TestCase
{
    protected static Ssh2Client $sshClient;

    public static function setUpBeforeClass(): void
    {
        self::$sshClient = Ssh2Client::connect(port: 2222)
            ->withAuthPassword(username: 'root', password: 'root');
    }

    public static function tearDownAfterClass(): void
    {
        self::$sshClient->disconnect();
    }
}
