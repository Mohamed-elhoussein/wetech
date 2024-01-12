<?php

namespace App\Enum;

abstract class ReportStatus {

    const RESOLVED = 'resolved';
    const NOT_RESOLVED = 'not_resolved';


    public static function toArray()
    {
        return [self::RESOLVED, self::NOT_RESOLVED];
    }
}
