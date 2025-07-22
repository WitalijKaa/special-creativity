<?php

namespace App\Models\Person;

use App\Models\World\Life;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $event_id
 * @property int $life_id
 * @property int $person_id
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonEventConnect query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonEventConnect whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonEventConnect whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonEventConnect wherePersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonEventConnect whereLifeId($value)
 *
 * @property-read \App\Models\Person\PersonEvent $event
 * @property-read \App\Models\Person\Person $person
 * @property-read \App\Models\World\Life $life
 *
 * @mixin \Eloquent
 */
class PersonEventConnect extends \Eloquent
{
    public const string TABLE_NAME = DB . '_event_connect';
    protected $table = self::TABLE_NAME;

    public $timestamps = false;
    protected $guarded = ['id'];

    public function event(): HasOne { return $this->hasOne(PersonEvent::class, 'id', 'event_id'); }
    public function person(): HasOne { return $this->hasOne(Person::class, 'id', 'person_id'); }
    public function life(): HasOne { return $this->hasOne(Life::class, 'id', 'life_id'); }
}
