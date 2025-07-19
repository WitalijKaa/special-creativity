<?php

namespace App\Requests\Person;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read integer $begin
 * @property-read integer $end
 * @property-read integer $type
 * @property-read integer $role
 * @property-read integer $parents
 * @property-read string|null $father
 * @property-read string|null $mother
 */
class PersonAddLifeRequest extends FormRequest
{
    protected $redirectRoute = 'web.person.list';

    public function rules(): array
    {
        return [
            'begin' => 'required|integer',
            'end' => 'required|integer',
            'type' => 'required|integer',
            'role' => 'required|integer',
            'parents' => 'required|integer',
            'father' => 'sometimes',
            'mother' => 'sometimes',
        ];
    }
}
