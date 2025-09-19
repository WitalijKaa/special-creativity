<?php

namespace App\Models\AiRequest;

use App\Models\Inteface\PoetryInterface;
use Illuminate\Support\Collection;

class TranslateToEnglish extends AiAbstractRequest
{
    public function apiEndPoint(): string { return 'translate_eng'; }
    public function forcedQuery(): array { return []; }

    public string $text = '';
    public string $ai;

    private function paragraphsChunks(): int { return 8; }

    private function translateApi(): string
    {
        return $this->withTimeout(300.0)->sendPost($this->toArray())['response'];
    }

    public function translatePoetryMass(Collection $poetry): Collection
    {
        return $this->translationPack($poetry) ?? $this->translationSplit($poetry);
    }

    private function translationPack(Collection $poetry): ?Collection
    {
        $return = new Collection();

        foreach ($poetry->chunk($this->paragraphsChunks()) as $clt) { /** @var $clt \Illuminate\Support\Collection */

            $this->text = trim($clt->implode(fn (PoetryInterface $paragraph) => $this->text .= $paragraph->text(), "\n\n"));
            $response = explode("\n\n", $this->translateApi());

            if ($clt->count() != count($response)) {
                return null;
            }

            foreach ($clt as $ix => $paragraph) {
                /** @var $paragraph PoetryInterface|\App\Models\Poetry\Poetry */
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
            $response = $translateParagraph->translateApi();
            $return->push($originalParagraph->translation($response, LL_ENG, $this->ai));
        });
        return $return;
    }
}
