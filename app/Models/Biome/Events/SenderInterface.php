<?php

namespace App\Models\Biome\Events;

interface SenderInterface
{
    public function send(EventInterface $event): void;
}
