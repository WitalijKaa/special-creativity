<?php

namespace App\Requests\World;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string $name
 * @property-read ?bool $is_honor
 * @property-read ?bool $is_relation
 * @property-read ?bool $is_work
 * @property-read ?bool $is_slave
 */
class EventTypeAddRequest extends FormRequest
{
    protected $redirectRoute = 'web.planet.params';

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:4|max:64',
            'is_honor' => 'sometimes|boolean',
            'is_relation' => 'sometimes|boolean',
            'is_work' => 'sometimes|boolean',
            'is_slave' => 'sometimes|boolean',
        ];
    }
}
