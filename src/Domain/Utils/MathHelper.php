<?php

namespace App\Domain\Utils;

class MathHelper
{
    /** bitwise AND */
    /** O(1) */
    public static function isPowerOfTwo(int $number): bool
    {
        return $number > 0 && ($number & ($number - 1)) === 0;
    }


//    /** bucle */
//    /** O(log n) */
//    private function isPowerOfTwo(int $number): bool
//    {
//        if ($number < 1) {
//            return false;
//        }
//
//        $div = $number;
//        while ($div % 2 === 0) {
//            $div = $div / 2;
//        }
//
//        return $div === 1;
//    }
}