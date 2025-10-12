<?php

namespace App\Http\Controllers\Person\Poetry;

class LifePoetryCompareAlphaAction extends LifePoetryCompareTechAction
{
    protected function filterLlmVariants(string $llmName)
    {
        foreach (config('basic.final_flow') as $llm) {
            if ($llm == $llmName) {
                return true;
            }
        }
        return false;
    }
}
