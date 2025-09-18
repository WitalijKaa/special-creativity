<?php

namespace App\Models\ApiModel\Attributes\TimeZones;

#[\Attribute]
class AttrUtcTimeZone extends AttrAbstractTimeZone
{
    public static function invoke(): string
    {
        return 'UTC';
    }
}
