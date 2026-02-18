<?php

namespace App\Models\Biome\Events;

use App\Models\Biome\Events\Core\BirthEvent;

class ListenerAdapter
{
    public const array EVENTS = [
        BirthEvent::EVENT_TYPE => BirthEvent::class,
    ];

    public static function getEventClassByType(string $type): string
    {
        return self::EVENTS[$type];
    }
}
