<?php

namespace App\Enum;

abstract class OrderStatus
{
    const COMPLETED = 'COMPLETED';
    const PENDING = 'PENDING';
    const CANCELED = 'CANCELED';

    public static function toArray()
    {
        return [self::COMPLETED, self::PENDING, self::CANCELED];
    }

    public static function toArrayWithColors()
    {
        return [self::COMPLETED => "rgb(0 248 104)", self::PENDING => "rgb(255 231 0)", self::CANCELED => "rgb(255 80 80)"];
    }
}
