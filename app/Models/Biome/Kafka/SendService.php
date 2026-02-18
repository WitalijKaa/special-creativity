<?php

namespace App\Models\Biome\Kafka;

use App\Models\Biome\Events\EventInterface;
use App\Models\Biome\Events\SenderInterface;
use Junges\Kafka\Facades\Kafka;

class SendService implements SenderInterface
{
    public const string HEADER_TYPE = 'type';

    public function send(EventInterface $event): void
    {
        Kafka::publish()
            ->onTopic($event->topic())
            ->withKafkaKey($event->key())
            ->withBody($event->toArray())
            ->withHeaders([self::HEADER_TYPE => $event->type()])
            ->send();
    }
}
