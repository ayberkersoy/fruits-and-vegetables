<?php

namespace App\Helper;

class QuantityType
{
    public const GRAM = 'g';
    public const KILOGRAM = 'kg';

    public static function getTypes(): array
    {
        return [
            self::GRAM,
            self::KILOGRAM,
        ];
    }
}