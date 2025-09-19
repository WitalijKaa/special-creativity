<?php

namespace App\Requests\Poetry;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read integer $begin
 * @property-read ?integer $end
 * @property-read string $lang
 * @property-read string $paragraph
 */
class ParagraphAddRequest extends FormRequest
{
    protected $redirectRoute = 'web.person.list';

    public function rules(): array
    {
        $lang = collect(\App\Models\Poetry\LanguageHelper::selectOptions())->implode(fn($item) => $item['opt'], ',');

        return [
            'paragraph' => 'required|string',
            'lang' => "required|string|in:$lang",
            'begin' => 'required|integer',
            'end' => 'sometimes',
        ];
    }
}
