<?php

namespace App\Providers;

use App\Models\Biome\Events\ReceiverInterface;
use App\Models\Biome\Events\SenderInterface;
use App\Models\Biome\Kafka\ReceiveService;
use App\Models\Biome\Kafka\SendService;
use Illuminate\Support\ServiceProvider;

class BiomeProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SenderInterface::class, function () {
            return new SendService();
        });
        $this->app->singleton(ReceiverInterface::class, function () {
            return new ReceiveService();
        });
    }

    public function boot(): void
    {
    }
}
