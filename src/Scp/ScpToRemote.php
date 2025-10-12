<?php

declare(strict_types=1);

namespace Cesargb\Ssh\Scp;

use Cesargb\Ssh\Exceptions\Files\FileNotFoundException;
use Cesargb\Ssh\Exceptions\Files\NonRecursiveCopyException;
use Cesargb\Ssh\Files\Path;
use Cesargb\Ssh\Ssh2Client;

final class ScpToRemote
{
    private bool $recursive = false;

    private int $createMode = 0644;

    private Path $localPath;

    public function __construct(private Ssh2Client $sshClient, string $localPath)
    {
        $this->localPath = new Path($localPath);
    }

    public function recursive(bool $recursive = true): self
    {
        $this->recursive = $recursive;

        return $this;
    }

    public function createMode(int $createMode): self
    {
        $this->createMode = $createMode;

        return $this;
    }

    public function to(string $path): bool
    {
        $this->validateLocalPath();

        $remotePath = new Path($path)->asRemote($this->sshClient);

        $resource = $this->sshClient->getResource();

        return ssh2_scp_send($resource, $this->localPath->path, $remotePath->path, $this->createMode);
    }

    private function validateLocalPath(): void
    {
        if (!$this->localPath->exists()) {
            throw new FileNotFoundException('Local path '.$this->localPath->path.' does not exist');
        }

        if ($this->localPath->isDir() && ! $this->recursive) {
            throw new NonRecursiveCopyException('Local path '.$this->localPath->path.' is a directory. Use recursive() method to copy directories');
        }
    }

    private function copyFileToFile(string $localPath, string $remotePath): bool
    {
        $resource = $this->sshClient->getResource();

        return ssh2_scp_send($resource, $localPath, $remotePath, $this->createMode);
    }
}
