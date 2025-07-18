<?php

namespace App\Requests\World;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string $life
 */
class LifeTypeAddRequest extends FormRequest
{
    protected $redirectRoute = 'web.planet.params';

    public function rules(): array
    {
        return [
            'life' => 'required|string|min:4|max:64',
        ];
    }
}
