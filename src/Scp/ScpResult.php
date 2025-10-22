<?php

declare(strict_types=1);

namespace Cesargb\Ssh\Scp;

use Cesargb\Ssh\Ssh2Client;
use Cesargb\Ssh\Traits\Disconnectable;

final class ScpResult
{
    use Disconnectable;

    public function __construct(Ssh2Client $sshClient, public readonly bool $success)
    {
        $this->sshClient = $sshClient;
    }
}
