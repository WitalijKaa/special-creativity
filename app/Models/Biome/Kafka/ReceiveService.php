<?php

namespace App\Models\Biome\Kafka;

use App\Models\Biome\Events\ListenerAdapter;
use App\Models\Biome\Events\ReceiverInterface;
use Junges\Kafka\Contracts\ConsumerMessage;
use Junges\Kafka\Contracts\MessageConsumer;
use Junges\Kafka\Facades\Kafka;

class ReceiveService implements ReceiverInterface
{
    public function receive(string $streamID, array $topics): void
    {
        $consumer = Kafka::consumer($topics, $streamID, config('kafka.brokers'))
            ->withHandler(function (ConsumerMessage $message, MessageConsumer $consumer) {
                $handlerClass = ListenerAdapter::getEventClassByType($message->getHeaders()[SendService::HEADER_TYPE]);
                /** @var \App\Models\Biome\Events\HandlerInterface $handlerClass */
                $isHandled = $handlerClass::handle($handlerClass::fromArray($message->getBody()));

                if ($isHandled) {
                    $consumer->commit($message);
                }
            })
            ->build();
        $consumer->consume();
    }
}
