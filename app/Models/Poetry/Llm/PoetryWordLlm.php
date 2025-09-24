<?php

namespace App\Models\Poetry\Llm;

use App\Models\ApiModel\BaseModel;
use App\Models\Poetry\PoetryWord;

#[\Attribute]
class PoetryWordLlm extends BaseModel
{
    public string $slavic;
    public string $english;
    public string $definition;

    public static function byDbModel(PoetryWord $poetryWord): static
    {
        $model = new static();
        $model->slavic = $poetryWord->word_ai;
        $model->english = $poetryWord->word_eng;
        $model->definition = $poetryWord->definition;
        return $model;
    }
}
