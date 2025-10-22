<?php

namespace Cesargb\Ssh\Traits;

trait ThrowableTrait
{
    protected bool $throwExceptions = false;

    public function throw(bool $throw = true): self
    {
        $this->throwExceptions = $throw;

        return $this;
    }
}
