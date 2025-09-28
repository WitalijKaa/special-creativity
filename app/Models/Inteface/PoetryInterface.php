<?php

namespace App\Models\Inteface;

interface PoetryInterface
{
    public function text(): string;
    public function llmModification(string $text, string $lang, string $llm): PoetryInterface;
}
