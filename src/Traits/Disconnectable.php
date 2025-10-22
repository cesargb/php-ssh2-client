<?php

namespace Cesargb\Ssh\Traits;

use Cesargb\Ssh\Ssh2Client;

trait Disconnectable
{
    protected ?Ssh2Client $sshClient = null;

    public function disconnect(): void
    {
        if ($this->sshClient !== null) {
            $this->sshClient->disconnect();
        }
    }
}
