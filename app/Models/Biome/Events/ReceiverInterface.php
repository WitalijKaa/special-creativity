<?php

namespace App\Models\Biome\Events;

interface ReceiverInterface
{
    public function receive(string $streamID, array $topics): void;
}
