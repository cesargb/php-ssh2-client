<?php

declare(strict_types=1);

namespace Cesargb\Ssh;

final class Connection
{
    private mixed $resource = false;
    private bool $authenticated = false;

    public function __construct(private Client $client)
    {
    }

    public function connect(): self
    {
        $this->authenticated = false;

        $this->resource = ssh2_connect($this->client->getHost(), (int) $this->client->getPort());

        return $this;
    }


    public function fingerPrint(): string
    {
        $this->mustBeConnected();

        $fingerprint = ssh2_fingerprint($this->resource);

        if ($fingerprint === false) {
            throw new \RuntimeException('Could not get fingerprint');
        }

        return $fingerprint;
    }

    public function withAuthKey(string $username, string $publicKey, string $privateKey, string $passphrase = ''): self
    {
        $this->mustBeConnected();

        if (!ssh2_auth_pubkey_file($this->resource, $username, $publicKey, $privateKey, $passphrase)) {
            throw new \RuntimeException('Could not authenticate with public key');
        }

        $this->authenticated = true;

        return $this;
    }


    public function exec(string $command): string
    {
        $this->mustBeConnected();

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
        $this->authenticated = false;

        if (is_resource($this->resource)) {
            ssh2_disconnect($this->resource);
        }
    }


    public function isConnected(): bool
    {
        return is_resource($this->resource);
    }

    public function isAuthenticated(): bool
    {
        return $this->authenticated;
    }

    public function getResource()
    {
        return $this->resource;
    }

    private function mustBeConnected(): void
    {
        if (!is_resource($this->resource)) {
            throw new \RuntimeException('Not connected');
        }
    }
}
