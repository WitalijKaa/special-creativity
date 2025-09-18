<?php

namespace App\Models\AiRequest;

use App\Models\ApiModel\BaseApiModel;

abstract class AiAbstractRequest extends BaseApiModel
{
    public const AI_LLAMA = 'llama';
    public const AI_GOOGLE = 'google';

    public function apiServer(): string
    {
        return 'http://127.0.0.1:8000/';
    }

    public function logErrorsStack(): array
    {
        return ['errors_temp'];
    }
}
