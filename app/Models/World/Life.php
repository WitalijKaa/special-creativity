<?php

namespace App\Models\World;


use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $begin
 * @property int $end
 * @property int $person_id
 * @property int $role
 * @property int $type_id
 * @property int|null $planet_id
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Life newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Life newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Life query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Life whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Life whereBegin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Life whereEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Life whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Life wherePersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Life wherePlanetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Life whereTypeId($value)
 *
 * @property-read string $role_name
 *
 * @property-read \App\Models\World\LifeType $type
 *
 * @mixin \Eloquent
 */
class Life extends \Eloquent
{
    public const string MAN = 'MAN';
    public const string WOMAN = 'WOMAN';
    public const string SPIRIT = 'SPIRIT';

    public const array ROLE = [1 => self::MAN, 2 => self::WOMAN, 3 => self::SPIRIT];

    protected $table = DB . '_life';
    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'begin' => 'integer',
            'end' => 'integer',
            'role' => 'integer',
        ];
    }

    public function getRoleNameAttribute()
    {
        return self::ROLE[$this->role];
    }

    public static function selectRoleOptions(): array
    {
        return [
            ['opt' => 1, 'lbl' => self::MAN],
            ['opt' => 2, 'lbl' => self::WOMAN],
            ['opt' => 3, 'lbl' => self::SPIRIT],
        ];
    }

    protected $guarded = ['id'];

    public function type(): HasOne { return $this->hasOne(LifeType::class, 'id', 'type_id'); }
}
