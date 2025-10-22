<?php

declare(strict_types=1);

namespace Cesargb\Ssh;

use Cesargb\Ssh\Exceptions\SshAuthenticateException;
use Cesargb\Ssh\Exceptions\SshConnectionException;
use Cesargb\Ssh\Exec\ExecCommand;
use Cesargb\Ssh\Exec\ExecResult;
use Cesargb\Ssh\Scp\Scp;

final class Ssh2Client
{
    private mixed $resource = false;

    private bool $authenticated = false;

    public function __construct(string $host = 'localhost', int $port = 22)
    {
        $this->authenticated = false;

        $this->resource = ssh2_connect(
            host: $host,
            port: $port,
            methods: [],
            callbacks: [
                'disconnect' => function () {
                    $this->resource = false;
                },
            ]
        );

        if ($this->resource === false) {
            throw new SshConnectionException('Could not connect to '.$host.':'.$port);
        }
    }

    public static function connect(string $host = 'localhost', int $port = 22): self
    {
        return new self($host, $port);
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

    public function withAuthPublicKey(string $username, string $publicKey, string $privateKey, string $passphrase = ''): self
    {
        $this->mustBeConnected();

        if (! ssh2_auth_pubkey_file($this->resource, $username, $publicKey, $privateKey, $passphrase)) {
            $this->disconnect();
            throw new SshAuthenticateException('Could not authenticate with public key');
        }

        $this->authenticated = true;

        return $this;
    }

    public function withAuthPassword(string $username, string $password): self
    {
        $this->mustBeConnected();

        if (! ssh2_auth_password($this->resource, $username, $password)) {
            $this->disconnect();
            throw new SshAuthenticateException('Could not authenticate with password');
        }

        $this->authenticated = true;

        return $this;
    }

    public function withAuthAgent(string $username): self
    {
        $this->mustBeConnected();

        if (! ssh2_auth_agent($this->resource, $username)) {
            $this->disconnect();
            throw new SshAuthenticateException('Could not authenticate with agent');
        }

        $this->authenticated = true;

        return $this;
    }

    public function withAuthNone(string $username): self
    {
        $this->mustBeConnected();

        $acceptedMethods = ssh2_auth_none($this->resource, $username);

        if ($acceptedMethods !== true) {
            $this->disconnect();

            throw new SshAuthenticateException('Could not authenticate with none, methods accepted: '.implode(', ', $acceptedMethods));
        }

        $this->authenticated = true;

        return $this;
    }

    public function disconnect(): void
    {
        $this->authenticated = false;

        if (is_resource($this->resource)) {
            ssh2_disconnect($this->resource);
            $this->resource = false;
        }
    }

    public function exec(string $command): ExecResult
    {
        return (new ExecCommand($this))->execute($command);
    }

    public function scp(): Scp
    {
        return new Scp($this);
    }

    public function isConnected(): bool
    {
        return is_resource($this->resource);
    }

    public function isAuthenticated(): bool
    {
        return $this->authenticated;
    }

    public function getResource(): mixed
    {
        return $this->resource;
    }

    private function mustBeConnected(): void
    {
        if (! is_resource($this->resource)) {
            throw new SshConnectionException('Not connected');
        }
    }
}
