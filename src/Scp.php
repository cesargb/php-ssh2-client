<?php

declare(strict_types=1);

namespace Cesargb\Ssh;

final class Scp
{
    public function __construct(private Ssh2Client $sshClient)
    {
    }

    public function send(string $localFile, string $remoteFile, int $createMode = 0644): void
    {
        if (! file_exists($localFile)) {
            throw new \InvalidArgumentException('Local file '.$localFile.' does not exist');
        }

        $resource = $this->sshClient->getResource();

        if (! ssh2_scp_send($resource, $localFile, $remoteFile, $createMode)) {
            throw new \RuntimeException('Could not upload file '.$localFile.' to '.$remoteFile);
        }
    }

    public function receive(string $remoteFile, string $localFile): void
    {
        $resource = $this->sshClient->getResource();

        if (! ssh2_scp_recv($resource, $remoteFile, $localFile)) {
            throw new \RuntimeException('Could not download file '.$remoteFile.' to '.$localFile);
        }
    }
}
