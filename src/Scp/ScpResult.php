<?php

declare(strict_types=1);

namespace Cesargb\Ssh\Scp;

use Cesargb\Ssh\SshResult;
use Cesargb\Ssh\SshSession;

final class ScpResult extends SshResult
{
    public function __construct(SshSession $session, private bool $success)
    {
        $this->session = $session;
    }

    public function succeeded(): bool
    {
        return $this->success;
    }
}
