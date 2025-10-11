<?php

declare(strict_types=1);

namespace Cesargb\Ssh\Exec;

use Cesargb\Ssh\Exceptions\SshConnectionException;
use Cesargb\Ssh\Exec\CommandResult;
use Cesargb\Ssh\Ssh2Client;

final class Exec
{
    public function __construct(private Ssh2Client $sshClient)
    {
    }

    public function execute(string $command): CommandResult
    {
        $resource = $this->sshClient->getResource();

        $streamOut = ssh2_exec($resource, $command);

        if ($streamOut === false) {
            throw new SshConnectionException('Could not execute command: '.$command);
        }

        $streamStdout = ssh2_fetch_stream($streamOut, SSH2_STREAM_STDIO);
        $streamStderr = ssh2_fetch_stream($streamOut, SSH2_STREAM_STDERR);

        stream_set_blocking($streamStdout, true);
        stream_set_blocking($streamStderr, true);

        $result_dio = stream_get_contents($streamStdout);
        $result_err = stream_get_contents($streamStderr);

        $metadata = stream_get_meta_data($streamOut);

        fclose($streamOut);

        return new CommandResult($result_dio, $result_err, $metadata);
    }
}
