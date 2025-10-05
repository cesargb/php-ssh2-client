<?php

namespace Cesargb\Ssh\Exceptions;

use Throwable;

class SshConnectionException extends SshException
{
    public function __construct(string $message = "SSH Connection Error")
    {
        parent::__construct($message);
    }
}
