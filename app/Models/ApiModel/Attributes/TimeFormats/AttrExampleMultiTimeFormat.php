<?php

namespace App\Models\ApiModel\Attributes\TimeFormats;

#[\Attribute]
class AttrExampleMultiTimeFormat extends AttrAbstractTimeFormat
{
    public static function invoke(): string|array
    {
        // first we will try 'Y-m-d H:i:s'
        return ['Y-m-d H:i:s', 'Y-m-d'];
    }
}
