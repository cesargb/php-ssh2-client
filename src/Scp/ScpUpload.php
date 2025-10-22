<?php

declare(strict_types=1);

namespace Cesargb\Ssh\Scp;

use Cesargb\Ssh\Exceptions\Files\FileNotFoundException;
use Cesargb\Ssh\Exceptions\Files\NonRecursiveCopyException;
use Cesargb\Ssh\Files\Path;
use Cesargb\Ssh\Ssh2Client;

final class ScpUpload
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

    public function to(string $path): ScpResult
    {
        $this->validateLocalPath();

        $remotePath = (new Path($path))->asRemote($this->sshClient);

        $this->validateRemotePath($remotePath);

        $success = $this->copy($this->localPath, $remotePath);
        return new ScpResult($this->sshClient, $success);
    }

    private function copy(Path $localPath, Path $remotePath): bool
    {
        if ($localPath->isDir()) {
            if (substr($localPath->path, -1) !== '/') {
                $remotePath = new Path(rtrim($remotePath->path, '/').'/'.basename($localPath->path));
                $this->sshClient->command()->execute('mkdir -p '.$remotePath->path);
            }

            return $this->copyRecursive($localPath, $remotePath);
        }

        return $this->copyFile($localPath, $remotePath);
    }

    private function copyRecursive(Path $localPath, Path $remotePath): bool
    {
        if ($localPath->isFile()) {
            return $this->copyFile($localPath, $remotePath);
        }

        $entries = scandir($localPath->path);

        if ($entries === false) {
            return false;
        }

        $success = true;

        foreach ($entries as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }

            $childLocal = new Path($localPath->path.'/'.$entry);
            $childRemote = new Path(rtrim($remotePath->path, '/').'/'.$entry);

            if ($childLocal->isDir()) {
                $this->sshClient->command()->execute('mkdir -p '.$childRemote->path);
            }

            $ok = $this->copyRecursive($childLocal, $childRemote);

            if (! $ok) {
                $success = false;
            }
        }

        return $success;
    }

    private function copyFile(Path $localPath, Path $remotePath): bool
    {
        $remoteFileName = $remotePath->isDir()
                ? rtrim($remotePath->path, '/').'/'.basename($localPath->path)
                : $remotePath->path;

        return ssh2_scp_send(
            $this->sshClient->getResource(),
            $localPath->path,
            $remoteFileName,
            $this->createMode
        );
    }

    private function validateLocalPath(): void
    {
        if (! $this->localPath->exists()) {
            throw new FileNotFoundException('Local path '.$this->localPath->path.' does not exist');
        }

        if ($this->localPath->isDir() && ! $this->recursive) {
            throw new NonRecursiveCopyException('Local path '.$this->localPath->path.' is a directory. Use recursive() method to copy directories');
        }
    }

    private function validateRemotePath(Path $remotePath): void
    {
        if ($this->localPath->isDir() && ! $remotePath->isDir()) {
            throw new NonRecursiveCopyException('Remote path '.$remotePath->path.' is not a directory. Use recursive() method to copy directories');
        }
    }
}
