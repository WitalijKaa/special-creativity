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

    public function translatePoetryMass(Collection $poetry): Collection
    {
        /** @var $paragraph PoetryInterface|\App\Models\Poetry\Poetry */
        $poetry->each(function (PoetryInterface $paragraph) {
            $this->text .= $paragraph->text() . "\n\n";
        });
        $this->text = trim($this->text);

        $response = $this->withTimeout(300.0)->sendPost($this->toArray())['response'];
        $response = explode("\n\n", $response);

        if ($poetry->count() != count($response)) {
            dd(implode("\n\n", $response));
        }

        $return = new Collection();
        foreach ($poetry as $ix => $paragraph) {
            $return->push($paragraph->translation($response[$ix], LL_ENG, $this->ai));
        }
        return $return;
    }
}
