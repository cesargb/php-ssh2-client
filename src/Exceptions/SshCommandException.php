<?php

declare(strict_types=1);

namespace Cesargb\Ssh\Exceptions;

use Cesargb\Ssh\Exec\ExecResult;

class SshCommandException extends SshSessionException
{
    public function __construct(public readonly ExecResult $result, string $message = 'SSH Command Error')
    {
        parent::__construct($message, $result->succeeded());
    }
}
