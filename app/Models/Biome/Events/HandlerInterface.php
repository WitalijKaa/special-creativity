<?php

namespace App\Models\Biome\Events;

interface HandlerInterface
{
    public static function handle(mixed $event): bool;
    public static function fromArray(array $json): static;
}
