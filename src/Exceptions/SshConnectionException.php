<?php

declare(strict_types=1);

namespace Cesargb\Ssh\Exceptions;

class SshConnectionException extends SshException
{
    public function __construct(string $message = 'SSH Connection Error')
    {
        parent::__construct($message);
    }
}
