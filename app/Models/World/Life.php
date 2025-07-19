<?php

namespace App\Models\World;


use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $begin
 * @property int $end
 * @property int $role
 * @property int $person_id
 * @property int $begin_force_person
 * @property int $begin_force_woman
 * @property int $parents_type_id
 * @property int|null $person_father_id
 * @property int|null $person_mother_id
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Life whereBeginForcePerson($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Life whereBeginForceWoman($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Life whereParentsTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Life wherePersonFatherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Life wherePersonMotherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Life whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Life wherePlanetId($value)
 *
 * @property-read string $role_name
 *
 * @property-read \App\Models\World\LifeType $type
 *
 * @mixin \Eloquent
 */
class Life extends \Eloquent
{
    public const int MAN = 1;
    public const int WOMAN = 2;
    public const int SPIRIT = 3;

    public const array ROLE = [self::MAN => 'MAN', self::WOMAN => 'WOMAN', self::SPIRIT => 'SPIRIT'];

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

    public function getRoleNameAttribute() // role_name
    {
        return self::ROLE[$this->role];
    }

    public static function selectRoleOptions(): array
    {
        return [
            ['opt' => self::MAN, 'lbl' => self::ROLE[self::MAN]],
            ['opt' => self::WOMAN, 'lbl' => self::ROLE[self::WOMAN]],
            ['opt' => self::SPIRIT, 'lbl' => self::ROLE[self::SPIRIT]],
        ];
    }

    protected $guarded = ['id'];

    public function type(): HasOne { return $this->hasOne(LifeType::class, 'id', 'type_id'); }
}
