<?php

namespace App\Models\AiRequest;

class TranslateToEnglish extends AiAbstractRequest
{
    public function apiEndPoint(): string { return 'translate_en'; }
    public function forcedQuery(): array { return []; }

    public string $text;
    public string $ai;
}
