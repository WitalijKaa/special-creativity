<?php

namespace App\Http\Controllers\Person\Poetry;

class LifePoetryCompareAction extends LifePoetryCompareTechAction
{
    protected function filterLlmVariants(string $llmName, string $lang)
    {
        return str_contains($llmName, FINAL_LLM);
    }
}
