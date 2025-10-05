<?php

declare(strict_types=1);

namespace Cesargb\Ssh;

final class Connection
{
    private mixed $resource = false;

    public function __construct(private Client $client)
    {
    }


    public function connect(): self
    {
        $this->resource = ssh2_connect($this->client->getHost(), (int) $this->client->getPort());

        var_dump($this->resource);
        return $this;
    }

    public function getResource()
    {
        return $this->resource;
    }

    public function isConnected(): bool
    {
        return is_resource($this->resource);
    }

    public function fingerPrint(): string
    {
        if (!is_resource($this->resource)) {
            throw new \RuntimeException('Not connected');
        }

        $fingerprint = ssh2_fingerprint($this->resource);

        if ($fingerprint === false) {
            throw new \RuntimeException('Could not get fingerprint');
        }

        return $fingerprint;
    }

    public function exec(string $command): string
    {
        if (!is_resource($this->resource)) {
            throw new \RuntimeException('Not connected');
        }

        $stream = ssh2_exec($this->resource, $command);

        if ($stream === false) {
            throw new \RuntimeException('Could not execute command: ' . $command);
        }

        stream_set_blocking($stream, true);

        $output = stream_get_contents($stream);

        fclose($stream);

        if ($output === false) {
            throw new \RuntimeException('Could not get output for command: ' . $command);
        }

        return $output;
    }


    public function disconnect(): void
    {
        if (is_resource($this->resource)) {
            ssh2_disconnect($this->resource);
        }
    }
}
