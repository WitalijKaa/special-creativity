<?php

namespace App\Http\Controllers\Person\Poetry;

class LifePoetryViewTechAction extends LifePoetryAction
{
    protected function filterLlmVariants(string $llmName, string $lang)
    {
        return true;
    }
}
