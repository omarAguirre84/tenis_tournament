<?php

namespace App\Tests\Unit\Domain\Utils;

use App\Domain\Utils\DateParser;
use PHPUnit\Framework\TestCase;

class DateParserTest extends TestCase
{
    public function testValidDate(): void
    {
        $date = DateParser::parseExact('2025-03-26');
        $this->assertInstanceOf(\DateTimeImmutable::class, $date);
        $this->assertEquals('2025-03-26', $date->format('Y-m-d'));
    }

    public function testInvalidDateFormat(): void
    {
        $date = DateParser::parseExact('26/03/2025');
        $this->assertNull($date);
    }

    public function testInvalidDateValue(): void
    {
        $date = DateParser::parseExact('2025-02-30');
        $this->assertNull($date);
    }
}

