<?php

namespace App\Models\Poetry;

class PoetryWordParsedDto
{
    public bool $isDashed = false;
    public bool $isCouple = false;

    /** @var array<string>|array<array<string>> second if $isDashed */
    public array $words = [];

    public string $definition;
}
