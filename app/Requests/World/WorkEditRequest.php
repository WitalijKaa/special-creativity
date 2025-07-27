<?php

namespace App\Requests\World;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string $name
 * @property-read ?int $capacity
 * @property-read ?int $consumers
 */
class WorkEditRequest extends FormRequest
{
    protected $redirectRoute = 'web.planet.params';

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:4|max:64',
            'capacity' => 'sometimes',
            'consumers' => 'sometimes',
        ];
    }
}
