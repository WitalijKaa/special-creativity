<?php

namespace App\Requests\World;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string $name
 * @property-read int $begin
 * @property-read int $end
 * @property-read ?int $capacity
 * @property-read ?int $consumers
 */
class WorkAddRequest extends FormRequest
{
    protected $redirectRoute = 'web.basic.space';

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:4|max:64',
            'begin' => 'required|int',
            'end' => 'required|int',
            'capacity' => 'sometimes',
            'consumers' => 'sometimes',
        ];
    }
}
