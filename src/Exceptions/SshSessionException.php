<?php

declare(strict_types=1);

namespace Cesargb\Ssh\Exceptions;

class SshSessionException extends SshException
{
    public readonly bool $succeeded;

    public function __construct(string $message = 'SSH Session Error', bool $succeeded = false)
    {
        parent::__construct($message);

        $this->succeeded = $succeeded;
    }
}
