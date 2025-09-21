<?php

namespace App\Requests\Poetry;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string $word
 * @property-read string $word_eng
 * @property-read string $definition
 * @property-read string $lang
 */
class PoetryWordAddRequest extends FormRequest
{
    protected $redirectRoute = 'web.planet.poetry-words';

    public function rules(): array
    {
        $lang = collect(\App\Models\Poetry\LanguageHelper::selectOptions())->implode(fn($item) => $item['opt'], ',');

        return [
            'word' => 'required|string|max:125',
            'word_eng' => 'required|string|max:125',
            'definition' => 'required|string',
            'lang' => "required|string|in:$lang",
        ];
    }
}
