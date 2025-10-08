<?php

declare(strict_types=1);

namespace Cesargb\Ssh;

final class SshClient
{
    private int $port = 22;

    public function __construct(private string $host) {}

    public function withPort(int $port): self
    {
        $this->port = $port;

        return $this;
    }

    public function connect(): Session
    {
        return new Session()->connect($this->host, $this->port);
    }
}
