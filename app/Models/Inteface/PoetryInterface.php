<?php

namespace App\Models\Inteface;

interface PoetryInterface
{
    public function text(): string;
    public function translation(string $text, string $lang, string $llm): PoetryInterface;
}
