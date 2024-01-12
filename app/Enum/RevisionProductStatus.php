<?php

namespace App\Enum;


abstract class RevisionProductStatus
{
    const PENDING = 'pending';
    const DENIED = 'denied';
    const ACCEPTED = 'accepted';

    public static function all()
    {
        return [
            self::PENDING,
            self::DENIED,
            self::ACCEPTED,
        ];
    }
}
