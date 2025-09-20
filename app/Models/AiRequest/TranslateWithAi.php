<?php

namespace App\Models\AiRequest;

use App\Models\Inteface\PoetryInterface;
use Illuminate\Support\Collection;

class TranslateWithAi extends AiAbstractRequest
{
    public function apiEndPoint(): string { return 'translate_' . $this->toLang; }
    public function forcedQuery(): array { return []; }

    public string $text = '';
    public string $ai;

    private string $toLang;

    private function translate(): string
    {
        return $this->withTimeout(600.0)->sendPost($this->toArray())['response'];
    }

    public function translatePoetryMass(Collection $poetry, string $toLang = LL_ENG): Collection
    {
        $this->toLang = $toLang;

        $this->text = trim($poetry->implode(fn (PoetryInterface $paragraph) => $paragraph->text(), "\n"));
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

    /*
    private function paragraphsChunks(): int { return 8; }

    private function translationPack(Collection $poetry): ?Collection
    {
        $return = new Collection();

        foreach ($poetry->chunk($this->paragraphsChunks()) as $clt) { /** @var $clt \Illuminate\Support\Collection * /

            $this->text = trim($clt->implode(fn (PoetryInterface $paragraph) => $this->text .= $paragraph->text(), "\n\n"));
            $response = explode("\n\n", $this->translate());

            if ($clt->count() != count($response)) {
                return null;
            }

            foreach ($clt as $ix => $paragraph) {
                /** @var $paragraph PoetryInterface|\App\Models\Poetry\Poetry * /
                $return->push($paragraph->translation($response[$ix], LL_ENG, $this->ai));
            }
        }

        $poetry->chunk($this->paragraphsChunks(), function (Collection $clt) use (&$return) {

        });
        return $return;
    }

    private function translationSplit(Collection $poetry): Collection
    {
        $return = new Collection();
        $poetry->each(function (PoetryInterface $originalParagraph) use ($return) {
            $translateParagraph = new static();
            $translateParagraph->ai = $this->ai;
            $translateParagraph->text = $originalParagraph->text();
            $response = $translateParagraph->translate();
            $return->push($originalParagraph->translation($response, LL_ENG, $this->ai));
        });
        return $return;
    }
    */
}
