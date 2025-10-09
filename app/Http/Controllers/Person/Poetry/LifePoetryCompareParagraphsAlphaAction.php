<?php

namespace App\Http\Controllers\Person\Poetry;

class LifePoetryCompareParagraphsAlphaAction extends LifePoetryCompareParagraphsTechAction
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
