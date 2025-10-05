<?php

namespace Cesargb\Ssh\Exceptions;

class SshAuthenticateException extends SshException
{
    public function __construct(string $message = "SSH Authentication Error")
    {
        parent::__construct($message);
    }
}
