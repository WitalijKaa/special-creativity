<?php

namespace App\Requests\Poetry;

use App\Models\Poetry\Llm\LlmConfig;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string $llm
 * @property-read string $llm_quality
 * @property-read bool $emotions
 */
class PoetryFinalRequest extends FormRequest
{
    protected $redirectRoute = 'web.person.list';

    public function rules(): array
    {
        $models = implode(',', config('basic.llm_models_final'));
        $quality = collect(\App\Models\Poetry\Llm\LlmConfig::selectQualityOptions())->implode(fn($item) => $item['opt'], ',');

        return [
            'llm' => "required|string|in:$models",
            'emotions' => "sometimes",
            'llm_quality' => "required|string|in:$quality",
        ];
    }

    public function llmConfig(): LlmConfig
    {
        $config = new LlmConfig($this->llm);
        $config->applyPipeParam($this->llm_quality);
        return $config;
    }
}
