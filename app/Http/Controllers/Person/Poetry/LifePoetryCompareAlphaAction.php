<?php

namespace App\Http\Controllers\Person\Poetry;

class LifePoetryCompareAlphaAction extends LifePoetryCompareTechAction
{
    protected function filterLlmVariants(string $llmName, string $lang)
    {
        return LL_RUS == $lang && array_any(V_MAIN, fn($specific) => $specific == $llmName);
    }
}
