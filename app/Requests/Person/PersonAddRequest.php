<?php

namespace App\Requests\Person;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string $name
 * @property-read string $nick
 * @property-read int $begin
 */
class PersonAddRequest extends FormRequest
{
    protected $redirectRoute = 'web.planet.params';

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:2|max:128',
            'nick' => 'required|string|min:2|max:32',
            'begin' => 'required|integer',
        ];
    }
}
