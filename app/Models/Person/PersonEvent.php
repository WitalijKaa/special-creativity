<?php

namespace App\Models\Person;

use App\Models\World\Life;
use App\Models\World\Work;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Schema;

/**
 * @property int $id
 * @property int $type_id
 * @property int $begin
 * @property int $end
 * @property ?string $comment
 * @property ?int $work_id
 * @property ?int $strong
 * @property int $life_id
 * @property int $person_id
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonEvent query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonEvent where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonEvent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonEvent whereBegin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonEvent whereEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonEvent whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonEvent whereWorkId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonEvent whereStrong($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonEvent whereLifeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonEvent wherePersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonEvent whereTypeId($value)
 * @method \Illuminate\Database\Eloquent\Builder<static>|PersonEvent whereTypeId($value)
 *
 * @property-read \App\Models\Person\EventType $type
 * @property-read \App\Models\Person\Person $person
 * @property-read \App\Models\World\Life $life
 * @property-read \App\Models\World\Work $work
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Person\PersonEventConnect[] $connections
 *
 * @mixin \Eloquent
 */
class PersonEvent extends \Eloquent
{
    public const string TABLE_NAME = DB . '_event';
    protected $table = self::TABLE_NAME;

    public function lifeOfPerson(int $personID): Life
    {
        $life = $this->life;
        if ($this->person_id != $personID) {
            foreach ($this->connections as $connect) {
                if ($connect->person_id == $personID) {
                    $life = $connect->life;
                }
            }
        }
        return $life;
    }

    public function archive(): array
    {
        $return = [
            'export' => 'event',
            'export_id' => $this->person->name,
            'export_type' => $this->life->type,

            'begin' => $this->begin,
            'end' => $this->end,
            'type' => $this->type->name,
            'comment' => $this->comment,
        ];

        if ($this->work_id) {
            $return['work'] = $this->work->name;
            if ($this->strong) { $return['strong'] = $this->strong; }
        }

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
            'strong' => 'integer',
        ];
    }
    public static function dbColumns(string $tableAlias = self::TABLE_NAME): array
    {
        return array_map(fn (string $column) => $tableAlias . '.' . $column, Schema::getColumnListing(static::TABLE_NAME));
    }

    public function type(): HasOne { return $this->hasOne(EventType::class, 'id', 'type_id'); }
    public function person(): HasOne { return $this->hasOne(Person::class, 'id', 'person_id'); }
    public function life(): HasOne { return $this->hasOne(Life::class, 'id', 'life_id'); }
    public function work(): HasOne { return $this->hasOne(Work::class, 'id', 'work_id'); }
    public function connections(): HasMany { return $this->hasMany(PersonEventConnect::class, 'event_id', 'id'); }
}
