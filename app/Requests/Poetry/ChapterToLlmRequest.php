<?php

namespace App\Requests\Poetry;

use App\Models\Poetry\Llm\LlmConfig;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string $llm
 * @property-read string $llm_mode
 * @property-read string $llm_quality
 * @property-read string $llm_rise_creativity
 */
class ChapterToLlmRequest extends FormRequest
{
    protected $redirectRoute = 'web.person.list';

    public function rules(): array
    {
        $models = implode(',', config('basic.llm_models'));
        $modes = collect(\App\Models\Poetry\Llm\LlmConfig::selectModeOptions())->implode(fn($item) => $item['opt'], ',');
        $quality = collect(\App\Models\Poetry\Llm\LlmConfig::selectQualityOptions())->implode(fn($item) => $item['opt'], ',');
        $rise_creativity = collect(\App\Models\Poetry\Llm\LlmConfig::selectRiseCreativityOptions())->implode(fn($item) => $item['opt'], ',');

        return [
            'llm' => "required|string|in:$models",
            'llm_mode' => "required|string|in:$modes",
            'llm_quality' => "required|string|in:$quality",
            'llm_rise_creativity' => "required|string|in:$rise_creativity",
            'from_llm' => "sometimes",
        ];
    }

    public function llmConfig(): LlmConfig
    {
        $config = new LlmConfig($this->llm);
        $config->applyPipeParam($this->llm_quality);
        $config->applyPipeParam($this->llm_mode ?? '');
        $config->applyPipeParam($this->llm_rise_creativity ?? '');
        return $config;
    }
}
