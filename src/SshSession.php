<?php

declare(strict_types=1);

namespace Cesargb\Ssh;

use Cesargb\Ssh\Exec\ExecCommand;
use Cesargb\Ssh\Scp\Scp;
use Cesargb\Ssh\Traits\Disconnectable;

final class SshSession
{
    use Disconnectable;

    public function __construct(private Ssh2Client $client)
    {
    }


    public function command(): ExecCommand
    {
        return new ExecCommand($this);
    }

    public function scp(): Scp
    {
        return new Scp($this);
    }

    public function getResource()
    {
        return $this->client->getResource();
    }

    public function isConnected(): bool
    {
        return $this->client->isConnected();
    }

    public function isAuthenticated(): bool
    {
        return $this->client->isAuthenticated();
    }
}
