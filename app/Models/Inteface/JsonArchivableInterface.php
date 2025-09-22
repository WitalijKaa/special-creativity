<?php

namespace App\Models\Inteface;

interface JsonArchivableInterface
{
    public function archive(): array;
    public static function fromArchive(array $archive): void;
}
