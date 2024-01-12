<?php

namespace App\Enum;

abstract class IdentityStatus {

    const APPROVED = 'approved';
    const PENDING = 'pending';
    const DENIED = 'denied';

    public static function toArray()
    {
        return [self::APPROVED, self::PENDING, self::DENIED];
    }
}
