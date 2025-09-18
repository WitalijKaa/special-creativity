<?php

namespace App\Models\ApiModel\Attributes\TimeZones;

#[\Attribute]
class AttrCaliforniaTimeZone extends AttrAbstractTimeZone
{
    public static function invoke(): string
    {
        // return 'Europe/Kyiv';
        return 'America/Los_Angeles';
    }
}
