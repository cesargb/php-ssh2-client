<?php

declare(strict_types=1);

namespace Cesargb\Ssh;

final class Client
{
    private string $port = '22';

    public function __construct(private string $host, private string $user)
    {
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): string
    {
        return $this->port;
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

    // private function __toString(): string
    // {
    //     return sprintf('%s@%s:%s', $this->user, $this->host, $this->port);
    // }
}
