<?php

namespace App\Requests\Poetry;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string $chapter
 * @property-read string $lang
 */
class ChapterAddRequest extends FormRequest
{
    protected $redirectRoute = 'web.person.list';

    public function rules(): array
    {
        $lang = collect(\App\Models\Poetry\LanguageHelper::selectOptions())->implode(fn($item) => $item['opt'], ',');

        return [
            'chapter' => 'required|string',
            'lang' => "required|string|in:$lang",
        ];
    }
}
