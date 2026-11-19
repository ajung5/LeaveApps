<?php

declare(strict_types=1);

namespace App\Support;

use InvalidArgumentException;
use OutOfRangeException;

final class IndonesianNumberWords
{
    private const UNITS = [
        'Nol', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima',
        'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas',
    ];

    /** Terbilang angka nonnegatif dalam bahasa Indonesia. */
    public static function spell(int $number): string
    {
        if ($number < 0) {
            throw new InvalidArgumentException('Angka tidak boleh negatif.');
        }
        return self::positive($number);
    }

    private static function positive(int $number): string
    {
        if ($number < 12) {
            return self::UNITS[$number];
        }
        if ($number < 20) {
            return self::positive($number - 10).' Belas';
        }
        if ($number < 100) {
            return self::multiple($number, 10, 'Puluh');
        }
        if ($number < 200) {
            return self::withRemainder('Seratus', $number - 100);
        }
        if ($number < 1000) {
            return self::multiple($number, 100, 'Ratus');
        }
        if ($number < 2000) {
            return self::withRemainder('Seribu', $number - 1000);
        }
        if ($number < 1_000_000) {
            return self::multiple($number, 1000, 'Ribu');
        }
        if ($number < 1_000_000_000) {
            return self::multiple($number, 1_000_000, 'Juta');
        }
        if ($number < 1_000_000_000_000) {
            return self::multiple($number, 1_000_000_000, 'Miliar');
        }
        if ($number < 1_000_000_000_000_000) {
            return self::multiple($number, 1_000_000_000_000, 'Triliun');
        }
        throw new OutOfRangeException('Angka di luar jangkauan terbilang.');
    }

    private static function multiple(int $number, int $divisor, string $unit): string
    {
        $count = intdiv($number, $divisor);
        $remainder = $number % $divisor;
        return self::withRemainder(self::positive($count).' '.$unit, $remainder);
    }

    private static function withRemainder(string $prefix, int $remainder): string
    {
        return $remainder === 0 ? $prefix : $prefix.' '.self::positive($remainder);
    }
}
