<?php

namespace App\Http\Controllers\Person\Poetry;

use App\Models\Poetry\Poetry;
use App\Models\World\Life;

class LifePoetryDeleteAction
{
    public function __invoke(int $life_id, string $lang, string $llm)
    {
        $llm = 'null' == $llm ? null : $llm;
        Poetry::whereLifeId($life_id)->whereLang($lang)->whereLlm($llm)->delete();

        return redirect()->back();
    }
}
