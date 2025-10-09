<?php

namespace App\Requests\Poetry;

/**
 * @property-read string $to_lang
 * @property-read ?string $from_llm
 * @property-read ?string $from_lang
 */
class ChapterTranslateRequest extends ChapterToLlmRequest
{
    protected $redirectRoute = 'web.person.list';

    public function rules(): array
    {
        $lang = collect(\App\Models\Poetry\LanguageHelper::selectOptions())->implode(fn($item) => $item['opt'], ',');
        $models = implode(',', config('basic.llm_models'));
        $quality = collect(\App\Models\Poetry\Llm\LlmConfig::selectQualityOptions())->implode(fn($item) => $item['opt'], ',');

        return [
            'llm' => "required|string|in:$models",
            'llm_quality' => "required|string|in:$quality",
            'from_llm' => "sometimes",
            'to_lang' => "required|string|in:$lang",
            'from_lang' => "sometimes",
        ];
    }
}
