<?php

namespace App\Models\ApiModel\Attributes\TimeFormats;

#[\Attribute]
class AttrExampleTimeFormat extends AttrAbstractTimeFormat
{
    public static function invoke(): string|array
    {
        return 'Y-m-d H:i:s T'; // 2022-02-24 05:13:13 EET
        // return 'Y-m-d\TH:i:s\+u'; // 2022-02-24T05:13:13+123456
    }
}
