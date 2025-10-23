<?php

declare(strict_types=1);

namespace Cesargb\Ssh\Scp;

use Cesargb\Ssh\SshSession;
use Cesargb\Ssh\Traits\ThrowableTrait;

final class Scp
{
    use ThrowableTrait;

    private bool $recursive = false;

    public function __construct(private SshSession $session)
    {
    }

    public function recursive(bool $recursive = true): self
    {
        $this->recursive = $recursive;

        return $this;
    }

    public function upload(string $localPath): ScpUpload
    {
        $scp = (new ScpUpload($this->session, $localPath))->throw($this->throwExceptions);

        if ($this->recursive) {
            $scp->recursive($this->recursive);
        }

        return $scp;
    }

    public function download(string $remotePath): ScpDownload
    {
        $scp = (new ScpDownload($this->session, $remotePath))->throw($this->throwExceptions);

        if ($this->recursive) {
            $scp->recursive($this->recursive);
        }

        return $scp;
    }
}
