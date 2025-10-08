<?php

declare(strict_types=1);

namespace Cesargb\Ssh;

use Cesargb\Ssh\Exceptions\SshAuthenticateException;
use Cesargb\Ssh\Exceptions\SshConnectionException;

final class Session
{
    private mixed $resource = false;

    private bool $authenticated = false;

    public function connect(string $host, int $port = 22, ?array $methods = null, array $callbacks = []): self
    {
        $this->authenticated = false;

        $this->resource = ssh2_connect($host, $port, $methods, array_merge(
            [
                'disconnect' => function () {
                    $this->resource = false;
                },
            ],
            $callbacks
        ));

        if ($this->resource === false) {
            throw new SshConnectionException('Could not connect to '.$host.':'.$port);
        }

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

    public function exec(string $command): string
    {
        $this->mustBeConnected();

        $stream = ssh2_exec($this->resource, $command);

        if ($stream === false) {
            throw new \RuntimeException('Could not execute command: '.$command);
        }

        stream_set_blocking($stream, true);

        $output = stream_get_contents($stream);

        fclose($stream);

        if ($output === false) {
            throw new \RuntimeException('Could not get output for command: '.$command);
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

    private function mustBeConnected(): void
    {
        if (! is_resource($this->resource)) {
            throw new SshConnectionException('Not connected');
        }
    }
}
