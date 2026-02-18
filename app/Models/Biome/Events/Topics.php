<?php

namespace App\Models\Biome\Events;

interface Topics
{
    public const array TOPICS = [
        self::TOPIC_CHILD_BIRTH,
    ];

    public const string TOPIC_CHILD_BIRTH = 'sc_birth';
}
