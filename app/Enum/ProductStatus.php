<?php

namespace App\Enum;

abstract class ProductStatus
{

    const NEW = 'NEW';
    const USED = 'USED';

    public static function toArray()
    {
        return [self::NEW, self::USED];
    }

    public static function fromArabicStatus($status)
    {
        if ($status === 'جديد') {
            return self::NEW;
        }

        return self::USED;
    }
}
