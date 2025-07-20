<?php

namespace App\Models\Person;

use App\Models\World\Life;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $type_id
 * @property int $begin
 * @property int $end
 * @property int $comment
 * @property int $life_id
 * @property int $person_id
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonEvent query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonEvent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonEvent whereBegin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonEvent whereEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonEvent whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonEvent whereLifeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonEvent wherePersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonEvent whereTypeId($value)
 *
 * @property-read \App\Models\World\LifeType $type
 * @property-read \App\Models\Person\Person $person
 * @property-read \App\Models\World\Life $life
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Person\PersonEventConnect[] $connections
 *
 * @mixin \Eloquent
 */
class PersonEvent extends \Eloquent
{
    protected $table = DB . '_event';

    public function archive(): array
    {
        $return = [
            'export' => 'event',
            'export_id' => $this->person->name,
            'export_type' => $this->life->type_id,

            'begin' => $this->begin,
            'end' => $this->end,
            'type' => $this->type_id,
            'comment' => $this->comment,
        ];

        $ix = 1;
        foreach ($this->connections as $connect) {
            $return['connect_' . $ix] = $connect->person->name;
            $ix++;
        }

        return $return;
    }

    public $timestamps = false;
    protected $guarded = ['id'];
    protected function casts(): array
    {
        return [
            'begin' => 'integer',
            'end' => 'integer',
        ];
    }

    public function type(): HasOne { return $this->hasOne(EventType::class, 'id', 'type_id'); }
    public function person(): HasOne { return $this->hasOne(Person::class, 'id', 'person_id'); }
    public function life(): HasOne { return $this->hasOne(Life::class, 'id', 'life_id'); }
    public function connections(): HasMany { return $this->hasMany(PersonEventConnect::class, 'event_id', 'id'); }
}
