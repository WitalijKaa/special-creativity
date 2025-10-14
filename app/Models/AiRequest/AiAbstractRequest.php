<?php

namespace App\Models\AiRequest;

use App\Models\ApiModel\BaseApiModel;
use App\Models\Collection\PoetryWordCollection;
use App\Models\Inteface\PoetryInterface;
use App\Models\Poetry\Llm\LlmConfig;
use App\Models\Poetry\Llm\PoetryLlm;
use App\Models\Poetry\PoetryWord;
use Illuminate\Support\Collection;

abstract class AiAbstractRequest extends BaseApiModel
{
    protected const string DEFAULT_SEPARATOR = "\n";
    protected const float TIMEOUT = 42000.0;

    public function apiServer(): string
    {
        return config('basic.llm_host');
    }

    public function logErrorsStack(): array
    {
        return ['errors_temp'];
    }

    public function apiHeaders(): array
    {
        return ['Llm-Nick' => $this->llm, 'Llm-Pipe' => $this->pipe, 'Llm-Sub-Mode' => $this->subMode];
    }

    /** @var \App\Models\Poetry\Llm\PoetryLlm|\App\Models\Poetry\Llm\PoetryPartsLlm */
    public $content;
    protected string $llm;
    protected string $pipe;
    protected string $subMode = 'no';

    protected function useLlm(string $separator = self::DEFAULT_SEPARATOR): array
    {
        return explode($separator, $this->withTimeout(self::TIMEOUT)->sendPost($this->toArray())['response']);
    }

    protected function prepareContent(Collection $poetry, string $lang): void
    {
        $this->content = new PoetryLlm();
        $this->prepareSpecialWords($poetry, $lang);
        $this->content->text = trim($poetry->implode(fn (PoetryInterface $paragraph) => $paragraph->text(), self::DEFAULT_SEPARATOR));
    }

    protected function prepareSpecialWords(Collection $poetry, string $lang): void
    {
        $this->content->special_words = PoetryWord::byLang($lang)->filterByParagraphs($poetry)->toLlm();
        $this->content->names = PoetryWordCollection::findAllNames($poetry);
    }

    protected static function prepareResponse(array $response, Collection $poetry, string $lang, string $llmName): Collection
    {
        if ($poetry->count() != count($response)) {
            \Log::channel('ai_weird')->notice(implode('####', $response));
            throw new \Exception('Ai response has ' . count($response) . ' while original text has ' . $poetry->count());
        }

        $return = new Collection();
        foreach ($poetry as $ix => $paragraph) {
            /** @var $paragraph PoetryInterface|\App\Models\Poetry\Poetry */
            $return->push($paragraph->llmModification($response[$ix], $lang, $llmName));
        }
        return $return;
    }

    public function useConfig(LlmConfig $config): void
    {
        $this->llm = $config->llm();
        $this->pipe = $config->pipeMode();
    }
}
