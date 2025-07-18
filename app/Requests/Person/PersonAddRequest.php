<?php

namespace App\Requests\Person;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string $name
 */
class PersonAddRequest extends FormRequest
{
    protected $redirectRoute = 'web.planet.params';

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:4|max:128',
        ];
    }
}
