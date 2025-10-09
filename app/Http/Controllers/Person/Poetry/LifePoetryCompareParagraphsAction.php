<?php

namespace App\Http\Controllers\Person\Poetry;

class LifePoetryCompareParagraphsAction extends LifePoetryCompareParagraphsTechAction
{
    protected function filterLlmVariants(string $llmName)
    {
        return str_contains($llmName, 'final');
    }
}
