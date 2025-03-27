<?php

namespace App\Domain\Utils;

class DateParser
{
    public static function parseExact(string $input, string $format = 'Y-m-d'): ?\DateTimeImmutable
    {
        $date = \DateTimeImmutable::createFromFormat($format, $input);
        $errors = \DateTimeImmutable::getLastErrors();

        if ($date === false) {
            return null;
        }

        if (is_array($errors) && ($errors['error_count'] > 0 || $errors['warning_count'] > 0)) {
            return null;
        }

        return $date;
    }
}
