<?php

declare(strict_types=1);

namespace Cesargb\Ssh;

use Cesargb\Ssh\Traits\Disconnectable;

abstract class SshResult
{
    use Disconnectable;

    abstract public function succeeded(): bool;
}
