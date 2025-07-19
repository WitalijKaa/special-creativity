<?php

namespace App\Models\World;

use App\Models\Person\Person;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $diff
 * @property int|null $year
 * @property int $person_id
 * @property int $life_id
 * @property int $type_id
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ForceEvent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ForceEvent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ForceEvent query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ForceEvent whereDiff($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ForceEvent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ForceEvent whereLifeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ForceEvent wherePersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ForceEvent whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ForceEvent whereYear($value)
 * @mixin \Eloquent
 *
 * @property-read \App\Models\World\ForceEventType $type
 *
 */
class ForceEvent extends \Eloquent
{
    protected $table = DB . '_event_force';
    public $timestamps = false;

    protected $guarded = ['id'];

    public static function createPerson(Person $model, int $year): void
    {
        $event = static::diffForce(ForceEventType::CREATE_PERSON, $model);
        $event->year = $year;
        $event->save();
        $model->save();
    }

    public static function liveLife(Person $model, Life $life): void
    {
        if (LifeType::PLANET != $life->type_id) {
            return;
        }

        $event = null;

        if (Life::MAN == $life->role) {
            $event = $life->person->creations->count() ? ForceEventType::PLANET_LIFE_MAN : ForceEventType::PLANET_LIFE_MAN_AT_BEGINNING;
        }
        else if (Life::WOMAN == $life->role) {
            $event = $life->may_be_girl_easy ? ForceEventType::PLANET_LIFE_WOMAN_RARE : ForceEventType::PLANET_LIFE_WOMAN;
        }

        if ($event) {
            $event = static::diffForce($event, $model);
            $event->save();
            $model->save();
        }
    }

    public static function diffForce(int $forceEvent, Person $person): static
    {
        $person->force_person += ForceEventType::DIFF_PERSON[$forceEvent];
        if ($person->force_person < 0) { $person->force_person = 0; }
        if ($person->force_person > Person::FORCE) { $person->force_person = Person::FORCE; }
        $person->save();

        $event = new static();
        $event->type_id = $forceEvent;
        $event->diff = ForceEventType::DIFF_PERSON[$forceEvent];
        $event->person_id = $person->id;
        $event->life_id = $person->refresh()->last_life->id;
        return $event;
    }

    public function diffSimple(int $force): int
    {
        $force += $this->diff;
        if ($force < 0) { $force = 0; }
        if ($force > Person::FORCE) { $force = Person::FORCE; }
        return $force;
    }

    public function type(): HasOne { return $this->hasOne(ForceEventType::class, 'id', 'type_id'); }
}
