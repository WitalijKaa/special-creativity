<?php

namespace App\Models\AiRequest;

use Illuminate\Support\Collection;

class ImproveWithLlm extends AiAbstractRequest
{
    public function apiEndPoint(): string { return 'make_text_better'; }
    public function forcedQuery(): array { return []; }

    public string $separator = '####';

    /**
     * @param \Illuminate\Support\Collection $poetry
     * @param string $toLang
     *
     * @return \Illuminate\Support\Collection|\App\Models\Poetry\Poetry[]
     */
    public function improveChapter(Collection $poetry): Collection
    {
        $this->prepareContent($poetry, LL_ENG);
        $response = $this->useLlm($this->separator);
        return static::prepareResponse($response, $poetry, LL_ENG, $this->llm . '.' . $this->pipe);
    }
}
