<?php

namespace App\Models\AiRequest;

use App\Models\Poetry\LanguageHelper;
use Illuminate\Support\Collection;

class TranslateWithLlm extends AiAbstractRequest
{
    public function apiEndPoint(): string { return 'translate_' . $this->toLang; }
    public function forcedQuery(): array { return []; }

    private string $toLang;

    /**
     * @param \Illuminate\Support\Collection $poetry
     * @param string $toLang
     *
     * @return \Illuminate\Support\Collection|\App\Models\Poetry\Poetry[]
     */
    public function translateChapter(Collection $poetry, string $toLang): Collection
    {
        $this->prepareContent($poetry, LanguageHelper::oppositeLang($toLang));
        $this->toLang = $toLang;
        $response = $this->useLlm();
        return static::prepareResponse($response, $poetry, $toLang, $this->llm . '.' . $this->pipe);
    }
}
