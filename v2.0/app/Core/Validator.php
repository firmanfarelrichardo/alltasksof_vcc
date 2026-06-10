<?php

declare(strict_types=1);

namespace App\Core;

final class Validator
{
    public static function required(?string $value): bool
    {
        return trim((string) $value) !== '';
    }

    public static function email(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function minLength(string $value, int $length): bool
    {
        return strlen($value) >= $length;
    }
}
