<?php

namespace App\Http\Controllers\Person\Poetry;

class LifePoetryCompareParagraphsAction extends LifePoetryAction
{
    protected function view($poetry, $llmVariants, $life, $wordsSlavic, $wordsEnglish)
    {
        return view('person.poetry.life-poetry-compare-paragraphs', compact('poetry', 'llmVariants', 'life', 'wordsSlavic', 'wordsEnglish'));
    }
}
