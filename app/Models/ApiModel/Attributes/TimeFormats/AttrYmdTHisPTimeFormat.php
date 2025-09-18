<?php

namespace App\Models\ApiModel\Attributes\TimeFormats;

#[\Attribute]
class AttrYmdTHisPTimeFormat extends AttrAbstractTimeFormat
{
    public static function invoke(): string|array
    {
        return 'Y-m-d\TH:i:sP';
    }
}
