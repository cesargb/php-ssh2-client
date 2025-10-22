<?php

declare(strict_types=1);

namespace Cesargb\Ssh\Scp;

use Cesargb\Ssh\Ssh2Client;
use Cesargb\Ssh\SshResult;

final class ScpResult extends SshResult
{
    public function __construct(Ssh2Client $sshClient, private bool $success)
    {
        $this->sshClient = $sshClient;
    }

    public function succeeded(): bool
    {
        return $this->success;
    }
}
