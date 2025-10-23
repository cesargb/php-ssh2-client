<?php

declare(strict_types=1);

namespace Cesargb\Ssh\Files;

use Cesargb\Ssh\SshSession;

class Path
{
    private ?bool $exists = null;

    private ?bool $isDir = null;

    private ?bool $isFile = null;

    private ?SshSession $session = null;

    public function __construct(public readonly string $path) {}

    public function asRemote(SshSession $session): self
    {
        $this->session = $session;

        return $this;
    }

    public function exists(): bool
    {
        if ($this->exists !== null) {
            return $this->exists;
        }

        $this->exists = $this->session
            ? $this->session->command()->execute('test -e '.escapeshellarg($this->path))->succeeded()
            : file_exists($this->path);

        return $this->exists;
    }

    public function isFile(): bool
    {
        if ($this->isFile !== null) {
            return $this->isFile;
        }

        $this->isFile = $this->session
            ? $this->session->command()->execute('test -f '.escapeshellarg($this->path))->succeeded()
            : is_file($this->path);

        return $this->isFile;
    }

    public function isDir(): bool
    {
        if ($this->isDir !== null) {
            return $this->isDir;
        }

        $this->isDir = $this->session
            ? $this->session->command()->execute('test -d '.escapeshellarg($this->path))->succeeded()
            : is_dir($this->path);

        return $this->isDir;
    }
}
