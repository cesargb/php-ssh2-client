<?php

declare(strict_types=1);

namespace Cesargb\Ssh;

final class Client
{
    private string $port = '22';

    public function __construct(private string $host)
    {
    }

    public function withPort(string $port): self
    {
        $this->port = $port;

        return $this;
    }

    public function connect(): Connection
    {
        return new Connection($this)->connect();
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): string
    {
        return $this->port;
    }
}
