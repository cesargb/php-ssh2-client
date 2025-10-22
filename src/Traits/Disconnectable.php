<?php

namespace Cesargb\Ssh\Traits;

use Cesargb\Ssh\SshSession;

trait Disconnectable
{
    protected ?SshSession $session = null;

    public function disconnect(): void
    {
        if (!$this->session) {
            return;
        }

        $this->session->disconnect();
    }
}
