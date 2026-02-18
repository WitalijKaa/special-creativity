<?php

namespace App\Console\Commands\GrowBiome;

use App\Models\Biome\Events\ReceiverInterface;
use App\Models\Biome\Events\Topics;
use Illuminate\Console\Command;

class ListenEvents extends Command
{
    protected $signature = 'biome:listen';

    protected $description = 'Biome simulation with Kafka (Listener)';

    public function handle(ReceiverInterface $receiver)
    {
        $receiver->receive('t10', Topics::TOPICS);
    }
}
