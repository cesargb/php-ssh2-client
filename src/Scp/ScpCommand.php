<?php

declare(strict_types=1);

namespace Cesargb\Ssh\Scp;

use Cesargb\Ssh\Ssh2Client;

final class ScpCommand
{
    public function __construct(private Ssh2Client $sshClient) {}

    public function fromLocal(string $path): ScpToRemote
    {
        return new ScpToRemote($this->sshClient, $path);
    }

    public function fromRemote(string $path): ScpToLocal
    {
        return new ScpToLocal($this->sshClient, $path);
    }
}
