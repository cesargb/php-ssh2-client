<?php

declare(strict_types=1);

namespace Cesargb\Ssh\Exec;

use Cesargb\Ssh\SshResult;
use Cesargb\Ssh\SshSession;

final class ExecResult extends SshResult
{
    private array $metaData = [];

    public function __construct(
        SshSession $session,
        public readonly string $output,
        public readonly string $errorOutput,
        array $metaData,
        public readonly string $command,
    )
    {
        $this->session = $session;
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
