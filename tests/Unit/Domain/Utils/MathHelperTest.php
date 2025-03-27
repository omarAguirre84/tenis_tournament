<?php

namespace App\Tests\Unit\Domain\Utils;

use App\Domain\Utils\MathHelper;
use PHPUnit\Framework\TestCase;

class MathHelperTest extends TestCase
{
    public function testPowerOfTwoReturnsTrue()
    {
        $this->assertTrue(MathHelper::isPowerOfTwo(2));
        $this->assertTrue(MathHelper::isPowerOfTwo(4));
        $this->assertTrue(MathHelper::isPowerOfTwo(8));
        $this->assertTrue(MathHelper::isPowerOfTwo(1024));
    }

    public function testNonPowerOfTwoReturnsFalse()
    {
        $this->assertFalse(MathHelper::isPowerOfTwo(0));
        $this->assertFalse(MathHelper::isPowerOfTwo(3));
        $this->assertFalse(MathHelper::isPowerOfTwo(6));
        $this->assertFalse(MathHelper::isPowerOfTwo(1000));
    }
}
