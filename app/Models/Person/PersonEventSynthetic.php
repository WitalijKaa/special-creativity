<?php

namespace App\Models\Person;

use App\Models\World\Life;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * @property-read null $work_id
 * @property-read \App\Models\Person\EventType $type
 * @property-read \App\Models\Person\Person $person
 * @property-read \App\Models\World\Life $life
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Person\PersonEventConnect[] $connections
 */
class PersonEventSynthetic
{
    public const int BIRTH = -1;
    public const int DEATH = -2;
    public const int ALLODS = -3;
    public const int NEW_PERSON = -4;

    private const array NAME = [
        self::BIRTH => 'birth on a Planet',
        self::DEATH => 'went to Allods',
        self::ALLODS => 'back to Allods',
        self::NEW_PERSON => 'Stvorio',
    ];

    public int $begin;
    public int $end;
    public ?string $comment = null;

    public int $type_id;
    private EventType $_type;
    public int $life_id;
    private Life $_life;
    public int $person_id;
    private Person $_person;

    public function __get(string $name)
    {
        if ('type' == $name) {
            if (empty($this->_type)) {
                if (0 > $this->type_id) {
                    $this->_type = new EventType;
                    $this->_type->name = self::NAME[$this->type_id];
                } else {
                    $this->_type = EventType::whereId($this->type_id)->first();
                }
            }
            return $this->_type;
        }
        if ('life' == $name) {
            if (empty($this->_life)) {
                $this->_life = Life::whereId($this->life_id)->first();
            }
            return $this->_life;
        }
        if ('person' == $name) {
            if (empty($this->_person)) {
                $this->_person = Person::whereId($this->person_id)->first();
            }
            return $this->_person;
        }
        if ('connections' == $name) {
            return new Collection();
        }
        if ('work_id' == $name) {
            return null;
        }
        throw new \ErrorException('Undefined property: ' . self::class . ' ::$' . $name);
    }

    public function lifeOfPerson(int $personID): Life
    {
        if (self::BIRTH == $this->type_id) {
            return $this->life;
        }

        return new Life(['role' => Life::SPIRIT]);
    }
}
