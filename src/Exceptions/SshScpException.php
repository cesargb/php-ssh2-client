<?php

declare(strict_types=1);

namespace Cesargb\Ssh\Exceptions;

use Cesargb\Ssh\Scp\ScpResult;

class SshScpException extends SshSessionException
{
    public function __construct(public readonly ScpResult $result, string $message = 'SSH SCP Error')
    {
        parent::__construct($message, $result->succeeded());
    }
}
