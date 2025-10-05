<?php

namespace Test;

use PHPUnit\Framework\TestCase;

class SshTest extends TestCase
{
    public function testExample()
    {
        $this->assertTrue($this->getValue() > 0);
    }

    private function getValue(): int
    {
        return 42;
    }
}
