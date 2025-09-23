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
    protected $redirectRoute = 'web.basic.space';

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:4|max:64',
            'is_honor' => 'sometimes',
            'is_relation' => 'sometimes',
            'is_work' => 'sometimes',
            'is_slave' => 'sometimes',
        ];
    }
}
