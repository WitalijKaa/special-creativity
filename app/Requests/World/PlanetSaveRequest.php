<?php

namespace App\Requests\World;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string $name
 * @property-read ?string $person
 */
class PlanetSaveRequest extends FormRequest
{
    protected $redirectRoute = 'web.planet.params';

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:4|max:32',
            'person' => 'sometimes',
        ];
    }
}
