<?php

namespace App\Models\Poetry\Llm;

use App\Models\ApiModel\BaseModel;
use Illuminate\Support\Collection;

class PoetryPartsLlm extends BaseModel
{
    public string $part_alpha = '';
    public string $part_beta = '';
    public ?string $part_emotional = null;
    public string $part_original = '';
    #[PoetryWordLlm]
    public Collection $special_words;
    public array $names = [];
}
