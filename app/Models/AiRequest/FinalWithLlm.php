<?php

namespace App\Models\AiRequest;

use App\Models\Poetry\Llm\PoetryPartsLlm;
use App\Models\Poetry\Poetry;
use Illuminate\Support\Collection;

class FinalWithLlm extends AiAbstractRequest
{
    public function apiEndPoint(): string { return 'make_final_text'; }
    public function forcedQuery(): array { return []; }

    public function combineParts(Poetry $original, Poetry $alpha, Poetry $beta, ?Poetry $emotion): string
    {
        $poetry = new Collection([$original]);
        $this->prepareContent($poetry, LL_RUS);
        $this->content->part_original = $original->text;
        $this->content->part_alpha = $alpha->text;
        $this->content->part_beta = $beta->text;
        $this->content->part_emotional = $emotion?->text;
        return implode(' ', $this->useLlm());
    }

    protected function prepareContent(Collection $poetry, string $lang): void
    {
        $this->content = new PoetryPartsLlm();
        $this->prepareSpecialWords($poetry, $lang);
    }
}
