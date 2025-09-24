<?php

namespace App\Models\AiRequest;

use App\Models\Collection\PoetryWordCollection;
use App\Models\Inteface\PoetryInterface;
use App\Models\Poetry\LanguageHelper;
use App\Models\Poetry\Llm\PoetryLlm;
use App\Models\Poetry\PoetryWord;
use Illuminate\Support\Collection;

class TranslateWithAi extends AiAbstractRequest
{
    public function apiEndPoint(): string { return 'translate_' . $this->toLang; }
    public function forcedQuery(): array { return []; }

    public PoetryLlm $content;
    public string $ai;

    private string $toLang;

    private function translate(): string
    {
        return $this->withTimeout(600.0)->sendPost($this->toArray())['response'];
    }

    public function translatePoetryMass(Collection $poetry, string $toLang = LL_ENG): Collection
    {
        $this->toLang = $toLang;
        $this->content = new PoetryLlm();
        $this->content->special_words = PoetryWord::byLang(LanguageHelper::oppositeLang($toLang))->filterByParagraphs($poetry)->toLlm();
        $this->content->names = PoetryWordCollection::findAllNames($poetry);

        $this->content->text = trim($poetry->implode(fn (PoetryInterface $paragraph) => $paragraph->text(), "\n"));
        $response = explode("\n", $this->translate());

        if ($poetry->count() != count($response)) {
            \Log::channel('ai_weird')->notice(implode('####', $response));
            throw new \Exception('Ai response has ' . count($response) . ' while original text has ' . $poetry->count());
        }

        $return = new Collection();
        foreach ($poetry as $ix => $paragraph) {
            /** @var $paragraph PoetryInterface|\App\Models\Poetry\Poetry */
            $return->push($paragraph->translation($response[$ix], LL_ENG, $this->ai));
        }
        return $return;
    }
}
