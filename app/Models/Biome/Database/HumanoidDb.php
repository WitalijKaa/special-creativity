<?php

namespace App\Models\Biome\Database;

use App\Models\Biome\BirthChildDesire;
use App\Models\Biome\Humanoid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string $name
 * @property bool $sex
 * @property int $born
 * @property int $child_at
 * @property int|null $mother_id
 * @property int|null $father_id
 *
 * @method static Builder<static>|HumanoidDb query()
 * @method static Builder<static>|HumanoidDb whereId($value)
 * @method static Builder<static>|HumanoidDb whereName($value)
 * @method static Builder<static>|HumanoidDb whereSex($value)
 * @method static Builder<static>|HumanoidDb whereBorn($value)
 * @method static Builder<static>|HumanoidDb whereChildAt($value)
 * @method static Builder<static>|HumanoidDb whereMotherId($value)
 * @method static Builder<static>|HumanoidDb whereFatherId($value)
 * @method Builder<static>|HumanoidDb whereId($value)
 * @method Builder<static>|HumanoidDb whereName($value)
 * @method Builder<static>|HumanoidDb whereSex($value)
 * @method Builder<static>|HumanoidDb whereBorn($value)
 * @method Builder<static>|HumanoidDb whereChildAt($value)
 * @method Builder<static>|HumanoidDb whereMotherId($value)
 * @method Builder<static>|HumanoidDb whereFatherId($value)
 *
 * @property HumanoidDb|null $mother
 * @property HumanoidDb|null $father
 * @property \Illuminate\Support\Collection<HumanoidDb> $children
 * @property \Illuminate\Support\Collection<HumanoidDb> $siblings
 *
 * @mixin \Eloquent
 */
class HumanoidDb extends \Eloquent
{
    public const string TABLE_NAME = 'kafka_humanoid';
    protected $table = self::TABLE_NAME;

    protected $guarded = ['id'];
    public $timestamps = false;

    protected $casts = [
        'sex' => 'boolean',
        'born' => 'integer',
        'child_at' => 'integer',
        'mother_id' => 'integer',
        'father_id' => 'integer',
    ];

    public static function allBornAt(int $year): Collection
    {
        return static::whereBorn($year)->get()->map(function (self $person) {
            return Humanoid::fromArray([
                'name' => $person->name,
                'sex' => $person->sex,
                'yearOfBorn' => $person->born,
                'yearOfDeath' => $person->born + 64,
                'desireChild' => $person->sex ? null :
                    BirthChildDesire::fromArray(['period' => 4, 'until' => 34, 'lastAt' => $person->child_at]),
            ]);
        });
    }

    public static function bornChild(string $name, int $age): void
    {
        static::where('name', $name)->update(['child_at' => $age]);
    }

    public function mother(): BelongsTo
    {
        return $this->belongsTo(self::class, 'mother_id', 'id');
    }

    public function father(): BelongsTo
    {
        return $this->belongsTo(self::class, 'father_id', 'id');
    }

    public function children(): HasMany
    {
        return $this->sex ? $this->hasMany(self::class, 'father_id', 'id') : $this->hasMany(self::class, 'mother_id', 'id');
    }

    public function siblings(): Collection
    {
        return static::whereKeyNot($this->id)
            ->where(function (Builder $builder) {
                if ($this->mother_id) {
                    $builder->where('mother_id', $this->mother_id);
                }
                if ($this->father_id) {
                    $builder->orWhere('father_id', $this->father_id);
                }
            })
            ->get();
    }
}
