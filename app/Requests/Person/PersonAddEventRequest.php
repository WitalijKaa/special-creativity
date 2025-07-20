<?php

namespace App\Requests\Person;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read integer $begin
 * @property-read integer $end
 * @property-read integer $type
 * @property-read ?string $comment
 */
class PersonAddEventRequest extends FormRequest
{
    protected $redirectRoute = 'web.person.list';

    public function rules(): array
    {
        return [
            'begin' => 'required|integer',
            'end' => 'required|integer',
            'type' => 'required|integer',
            'comment' => 'sometimes',
        ];
    }
}
