<?php

namespace Tests;

use Cesargb\Ssh\Ssh2Client;
use Cesargb\Ssh\SshSession;
use PHPUnit\Framework\TestCase;

abstract class SshCase extends TestCase
{
    protected static SshSession $sshSession;

    public static function setUpBeforeClass(): void
    {
        self::$sshSession = Ssh2Client::connect(port: 2222)
            ->withAuthPassword(username: 'root', password: 'root');
    }

    public static function tearDownAfterClass(): void
    {
        self::$sshSession->disconnect();
    }
}
