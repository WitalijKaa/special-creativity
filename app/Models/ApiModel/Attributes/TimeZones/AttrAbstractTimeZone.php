<?php

namespace App\Models\ApiModel\Attributes\TimeZones;

abstract class AttrAbstractTimeZone
{
    abstract public static function invoke(): string;
}
