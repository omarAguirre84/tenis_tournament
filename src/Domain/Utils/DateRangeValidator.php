<?php

namespace App\Domain\Utils;

class DateRangeValidator
{
    public static function validate(\DateTimeInterface $from, \DateTimeInterface $to): void
    {
        if ($from > $to) {
            throw new \InvalidArgumentException('From date cannot be less than or equal to date.');
        }
    }
}
