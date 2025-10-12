<?php

declare(strict_types=1);

namespace Cesargb\Ssh\Scp;

use Cesargb\Ssh\Ssh2Client;

final class ScpToRemote
{
    private bool $recursive = false;

    private int $createMode = 0644;

    public function __construct(private Ssh2Client $sshClient, private string $localPath)
    {
    }

    public function recursive(bool $recursive = true): self
    {
        $this->recursive = $recursive;

        return $this;
    }

    public function createMode(int $createMode): self
    {
        $this->createMode = $createMode;

        return $this;
    }

    public function to(string $path): bool
    {
        if (! file_exists($this->localPath)) {
            throw new \InvalidArgumentException('Local path '.$this->localPath.' does not exist');
        }

        if (! $this->recursive && ! is_dir($this->localPath)) {
            throw new \InvalidArgumentException('Local path '.$this->localPath.' is not a file');
        }

        $resource = $this->sshClient->getResource();

        return ssh2_scp_send($resource, $this->localPath, $path, $this->createMode);
    }

    private function copyFileToFile(string $localPath, string $remotePath): bool
    {
        $resource = $this->sshClient->getResource();

        return ssh2_scp_send($resource, $localPath, $remotePath, $this->createMode);
    }

    private function isLocalPathDirectory(): bool
    {
        return is_dir($this->localPath);
    }


    private function isRemotePathExists(string $path): bool
    {
        $result = $this->sshClient->exec('if [ -e '.$path.' ]; then echo "true"; else echo "false"; fi');

        return trim($result->output) === 'true';
    }

    private function isRemotePathDirectory(string $path): bool
    {
        $result = $this->sshClient->exec('if [ -d '.$path.' ]; then echo "true"; else echo "false"; fi');

        return trim($result->output) === 'true';
    }
}
