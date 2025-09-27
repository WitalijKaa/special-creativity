<?php

namespace App\Requests\Poetry;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string $to_lang
 * @property-read ?string $from_llm
 * @property-read ?string $from_lang
 * @property-read string $llm
 * @property-read string $llm_mode
 * @property-read string $llm_quality
 * @property-read string $llm_rise_creativity
 */
class ChapterTranslateRequest extends FormRequest
{
    protected $redirectRoute = 'web.person.list';

    public function rules(): array
    {
        $lang = collect(\App\Models\Poetry\LanguageHelper::selectOptions())->implode(fn($item) => $item['opt'], ',');
        $models = implode(',', config('basic.llm_models'));
        $modes = collect(\App\Models\Poetry\Llm\LlmConfig::selectModeOptions())->implode(fn($item) => $item['opt'], ',');
        $quality = collect(\App\Models\Poetry\Llm\LlmConfig::selectQualityOptions())->implode(fn($item) => $item['opt'], ',');
        $rise_creativity = collect(\App\Models\Poetry\Llm\LlmConfig::selectRiseCreativityOptions())->implode(fn($item) => $item['opt'], ',');

        return [
            'to_lang' => "required|string|in:$lang",
            'from_llm' => "sometimes",
            'from_lang' => "sometimes",
            'llm' => "required|string|in:$models",
            'llm_mode' => "required|string|in:$modes",
            'llm_quality' => "required|string|in:$quality",
            'llm_rise_creativity' => "required|string|in:$rise_creativity",
        ];
    }
}
