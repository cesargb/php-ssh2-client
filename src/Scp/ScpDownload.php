<?php

declare(strict_types=1);

namespace Cesargb\Ssh\Scp;

use Cesargb\Ssh\Files\Path;
use Cesargb\Ssh\SshSession;
use Cesargb\Ssh\Traits\ThrowableTrait;

final class ScpDownload
{
    use ThrowableTrait;

    private bool $recursive = false;

    private Path $remotePath;

    public function __construct(private SshSession $session, string $remotePath)
    {
        $this->remotePath = (new Path($remotePath))->asRemote($this->session);
    }

    public function recursive(bool $recursive = true): self
    {
        $this->recursive = $recursive;

        return $this;
    }

    public function to(string $path): ScpResult
    {
        $success = $this->copyFile($this->remotePath, new Path($path));
        return new ScpResult($this->session, $success);
    }

    private function copyFile(Path $remotePath, Path $localPath): bool
    {
        $localFileName = $localPath->isDir()
                ? rtrim($localPath->path, '/').'/'.basename($remotePath->path)
                : $localPath->path;

        return ssh2_scp_recv(
            $this->session->getResource(),
            $remotePath->path,
            $localFileName
        );
    }
}
