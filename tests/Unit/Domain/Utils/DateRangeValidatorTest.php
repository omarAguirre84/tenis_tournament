<?php

namespace App\Tests\Unit\Domain\Utils;

use App\Domain\Utils\DateRangeValidator;
use PHPUnit\Framework\TestCase;

class DateRangeValidatorTest extends TestCase
{
    public function testValidDateRange(): void
    {
        $from = new \DateTimeImmutable('2025-01-01');
        $to = new \DateTimeImmutable('2025-01-31');

        $this->expectNotToPerformAssertions();
        DateRangeValidator::validate($from, $to);
    }

    public function testInvalidDateRange(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('From date cannot be less than or equal to date.');

        $from = new \DateTimeImmutable('2025-02-01');
        $to = new \DateTimeImmutable('2025-01-01');

        DateRangeValidator::validate($from, $to);
    }
}
