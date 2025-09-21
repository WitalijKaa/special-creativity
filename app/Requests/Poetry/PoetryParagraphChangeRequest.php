<?php

namespace App\Requests\Poetry;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string $text
 */
class PoetryParagraphChangeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'text' => 'required|string',
        ];
    }
}
