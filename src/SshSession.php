<?php

declare(strict_types=1);

namespace Cesargb\Ssh;

use Cesargb\Ssh\Exec\ExecCommand;
use Cesargb\Ssh\Scp\Scp;
use Cesargb\Ssh\Traits\Disconnectable;
use Cesargb\Ssh\Traits\ThrowableTrait;

final class SshSession
{
    use Disconnectable;
    use ThrowableTrait;

    public function __construct(private Ssh2Client $client)
    {
    }

    public function command(): ExecCommand
    {
        return new ExecCommand($this)->throw($this->throwExceptions);
    }

    public function scp(): Scp
    {
        return new Scp($this)->throw($this->throwExceptions);
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
