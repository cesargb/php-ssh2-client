<?php

declare(strict_types=1);

namespace Cesargb\Ssh\Scp;

use Cesargb\Ssh\Files\Path;
use Cesargb\Ssh\Ssh2Client;

final class ScpDownload
{
    private bool $recursive = false;

    private Path $remotePath;

    public function __construct(private Ssh2Client $sshClient, string $remotePath)
    {
        $this->remotePath = (new Path($remotePath))->asRemote($sshClient);
    }

    public function recursive(bool $recursive = true): self
    {
        $this->recursive = $recursive;

        return $this;
    }

    public function to(string $path): bool
    {
        return $this->copyFile($this->remotePath, new Path($path));
    }

    private function copyFile(Path $remotePath, Path $localPath): bool
    {
        $localFileName = $localPath->isDir()
                ? rtrim($localPath->path, '/').'/'.basename($remotePath->path)
                : $localPath->path;

        return ssh2_scp_recv(
            $this->sshClient->getResource(),
            $remotePath->path,
            $localFileName
        );
    }
}
