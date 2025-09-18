<?php

namespace App\Models\ApiModel\Attributes\TimeFormats;

#[\Attribute]
class AttrYmdHisTimeFormat extends AttrAbstractTimeFormat
{
    public static function invoke(): string|array
    {
        return 'Y-m-d H:i:s';
    }
}
