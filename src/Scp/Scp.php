<?php

declare(strict_types=1);

namespace Cesargb\Ssh\Scp;

use Cesargb\Ssh\Ssh2Client;

final class Scp
{
    private bool $recursive = false;

    public function __construct(private Ssh2Client $sshClient)
    {
    }

    public function recursive(bool $recursive = true): self
    {
        $this->recursive = $recursive;

        return $this;
    }

    public function upload(string $localPath): ScpToRemote
    {
        $scp = new ScpToRemote($this->sshClient, $localPath);

        if ($this->recursive) {
            $scp->recursive($this->recursive);
        }

        return $scp;
    }

    public function download(string $remotePath): ScpToLocal
    {
        $scp = new ScpToLocal($this->sshClient, $remotePath);

        if ($this->recursive) {
            $scp->recursive($this->recursive);
        }

        return $scp;
    }
}
