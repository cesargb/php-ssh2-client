<?php

declare(strict_types=1);

namespace Cesargb\Ssh\Exec;

use Cesargb\Ssh\Exceptions\SshConnectionException;
use Cesargb\Ssh\SshSession;

final class ExecCommand
{
    public function __construct(private SshSession $session) {}

    public function execute(string $command): ExecResult
    {
        $resource = $this->session->getResource();

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

        return new ExecResult($this->session, $result_dio, $result_err, $metadata);
    }
}
