<?php

declare(strict_types=1);

namespace Cesargb\Ssh\Files;

use Cesargb\Ssh\Ssh2Client;

class Path
{
    private ?bool $exists = null;

    private ?bool $isDir = null;

    private ?bool $isFile = null;

    private ?Ssh2Client $sshClient = null;

    public function __construct(public readonly string $path) {}

    public function asRemote(Ssh2Client $sshClient): self
    {
        $this->sshClient = $sshClient;

        return $this;
    }

    public function exists(): bool
    {
        if ($this->exists !== null) {
            return $this->exists;
        }

        $this->exists = $this->sshClient
            ? $this->sshClient->exec('test -e '.escapeshellarg($this->path))->succeeded()
            : file_exists($this->path);

        return $this->exists;
    }

    public function isFile(): bool
    {
        if ($this->isFile !== null) {
            return $this->isFile;
        }

        $this->isFile = $this->sshClient
            ? $this->sshClient->exec('test -f '.escapeshellarg($this->path))->succeeded()
            : is_file($this->path);

        return $this->isFile;
    }

    public function isDir(): bool
    {
        if ($this->isDir !== null) {
            return $this->isDir;
        }

        $this->isDir = $this->sshClient
            ? $this->sshClient->exec('test -d '.escapeshellarg($this->path))->succeeded()
            : is_dir($this->path);

        return $this->isDir;
    }
}
