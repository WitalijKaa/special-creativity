<?php

namespace App\Http\Controllers\Person\Poetry;

class LifePoetryCompareTechAction extends LifePoetryAction
{
    protected function view($poetry, $llmVariants, $life, $wordsSlavic, $wordsEnglish, $llmAllNames = [])
    {
        return view('person.poetry.life-poetry-compare-paragraphs', compact('poetry', 'llmVariants', 'life', 'wordsSlavic', 'wordsEnglish'));
    }

    protected function filterLlmVariants(string $llmName)
    {
        return true;
    }
}
