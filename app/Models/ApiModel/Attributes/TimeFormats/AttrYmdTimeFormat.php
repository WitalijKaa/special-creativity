<?php

namespace App\Models\ApiModel\Attributes\TimeFormats;

#[\Attribute]
class AttrYmdTimeFormat extends AttrAbstractTimeFormat
{
    public static function invoke(): string|array
    {
        return 'Y-m-d';
    }
}
