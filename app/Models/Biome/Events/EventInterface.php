<?php

namespace App\Models\Biome\Events;

interface EventInterface
{
    public function topic(): string;
    public function type(): string;
    public function key(): string;
    public function toArray(): array;
}
