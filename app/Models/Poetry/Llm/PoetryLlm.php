<?php

namespace App\Models\Poetry\Llm;

use App\Models\ApiModel\BaseModel;
use Illuminate\Support\Collection;

class PoetryLlm extends BaseModel
{
    public string $text = '';
    #[PoetryWordLlm]
    public Collection $special_words;
    public array $names = [];
}
