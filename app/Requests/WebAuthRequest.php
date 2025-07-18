<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string $login
 * @property-read string $pass
 */
class WebAuthRequest extends FormRequest
{
    protected $redirectRoute = 'login';

    public function rules(): array
    {
        return [
            'login' => 'required|string|min:4|max:32|regex:/^[a-z]+$/',
            'pass' => 'required|string|min:4|max:32|regex:/^[a-zA-Z0-9!#\-_.]+$/'
        ];
    }

    public function messages(): array
    {
        return [
            'login.*' => 'Correct login required (latin small only)',
            'pass.*' => 'Correct pass required (latin, digits, !#-_.)',
        ];
    }
}
