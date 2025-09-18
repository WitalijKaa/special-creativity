<?php

namespace App\Models\ApiModel\Attributes\TimeFormats;

abstract class AttrAbstractTimeFormat
{
    abstract public static function invoke(): string|array;
}
