<?php

declare(strict_types=1);

namespace Cesargb\Ssh\Exec;

use Cesargb\Ssh\Ssh2Client;
use Cesargb\Ssh\SshResult;

final class ExecResult extends SshResult
{
    private array $metaData = [];

    public function __construct(
        Ssh2Client $sshClient,
        public readonly string $output,
        public readonly string $errorOutput,
        array $metaData = []
        )
    {
        $this->sshClient = $sshClient;
        $this->metaData = $metaData;
    }

    public function getExitStatus(): ?int
    {
        return $this->metaData['exit_status'] ?? null;
    }

    public function succeeded(): bool
    {
        if ($this->getExitStatus() !== null) {
            return $this->getExitStatus() === 0;
        }

        return $this->errorOutput === '';
    }

    public function __toString(): string
    {
        return $this->succeeded() ? $this->output : $this->errorOutput;
    }
}
