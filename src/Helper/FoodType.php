<?php

namespace App\Helper;

class FoodType
{
    public const FRUIT = 'fruit';
    public const VEGETABLE = 'vegetable';

    public static function getTypes(): array
    {
        return [
            self::FRUIT,
            self::VEGETABLE,
        ];
    }
}