<?php

namespace App\Http\Controllers\Person\Poetry;

class LifePoetryCompareAction extends LifePoetryCompareTechAction
{
    protected function filterLlmVariants(string $llmName)
    {
        return str_contains($llmName, 'final');
    }
}
