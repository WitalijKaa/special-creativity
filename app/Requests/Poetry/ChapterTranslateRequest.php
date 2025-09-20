<?php

namespace App\Requests\Poetry;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string $to_lang
 * @property-read string $llm
 */
class ChapterTranslateRequest extends FormRequest
{
    protected $redirectRoute = 'web.person.list';

    public function rules(): array
    {
        $lang = collect(\App\Models\Poetry\LanguageHelper::selectOptions())->implode(fn($item) => $item['opt'], ',');
        $models = implode(',', config('basic.llm_models'));

        return [
            'to_lang' => "required|string|in:$lang",
            'llm' => "required|string|in:$models",
        ];
    }
}
