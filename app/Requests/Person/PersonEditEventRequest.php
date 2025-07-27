<?php

namespace App\Requests\Person;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read integer $begin
 * @property-read integer $end
 * @property-read ?integer $strong
 * @property-read ?string $comment
 */
class PersonEditEventRequest extends FormRequest
{
    protected $redirectRoute = 'web.person.list';

    public function rules(): array
    {
        return [
            'begin' => 'required|integer',
            'end' => 'required|integer',
            'strong' => 'sometimes',
            'comment' => 'sometimes',
        ];
    }
}
