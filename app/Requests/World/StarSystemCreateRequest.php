<?php

namespace App\Requests\World;

use App\Models\Person\Person;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string $name
 * @property-read int $days
 * @property-read int $hours

 * @property-read string $person
 * @property-read string $nick
 * @property-read int $force
 *
 * @property-read int $force_create
 * @property-read int $force_man_up
 * @property-read int $force_woman_up
 * @property-read int $force_woman_special_up
 * @property-read int $force_woman_man_allowed
 * @property-read int $force_man_first_up
 * @property-read int $force_woman_first_up
 * @property-read int $force_man_min
 * @property-read int $force_woman_min
 */
class StarSystemCreateRequest extends FormRequest
{
    protected $redirectRoute = 'web.planet.once-create';

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:4|max:32',
            'person' => 'required|string|min:2|max:128',
            'nick' => 'required|string|min:2|max:32',
            'force' => 'required|int|min:0|max:' . Person::FORCE,

            'days' => 'required|int|min:42|max:2048',
            'hours' => 'required|int|min:8|max:128',

            'force_create' => 'required|int|min:1|max:' . Person::FORCE,
            'force_man_up' => 'required|int|min:1|max:' . Person::FORCE,
            'force_woman_up' => 'required|int|min:0|max:' . Person::FORCE,
            'force_woman_special_up' => 'required|int|min:0|max:' . Person::FORCE,
            'force_woman_man_allowed' => 'required|int|min:1|max:42',
            'force_man_first_up' => 'required|int|min:0|max:' . Person::FORCE,
            'force_woman_first_up' => 'required|int|min:0|max:' . Person::FORCE,
            'force_man_min' => 'required|int|min:5|max:2048',
            'force_woman_min' => 'required|int|min:5|max:2048',
        ];
    }
}
