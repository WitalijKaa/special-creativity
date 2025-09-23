<?php

namespace App\Models\World;

use App\Models\Inteface\JsonArchivableInterface;

/**
 * @property int $id
 * @property string $name
 * @property int $days
 * @property int $hours
 * @property int $force
 * @property int $force_create
 * @property int $force_man_up
 * @property int $force_woman_up
 * @property int $force_woman_special_up
 * @property int $force_woman_man_allowed
 * @property int $force_man_first_up
 * @property int $force_woman_first_up
 * @property int $force_man_min
 * @property int $force_woman_min
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Planet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Planet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Planet query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Planet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Planet whereName($value)
 * @mixin \Eloquent
 */
class Planet extends \Eloquent implements JsonArchivableInterface
{
    public const int HOME_PLANET = 1;

    protected $table = DB . '_planet';
    public $timestamps = false;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'days' => 'integer',
            'hours' => 'integer',
            'force' => 'integer',
            'force_create' => 'integer',
            'force_man_up' => 'integer',
            'force_woman_up' => 'integer',
            'force_woman_special_up' => 'integer',
            'force_woman_man_allowed' => 'integer',
            'force_man_first_up' => 'integer',
            'force_woman_first_up' => 'integer',
            'force_man_min' => 'integer',
            'force_woman_min' => 'integer',
        ];
    }

    public function archive(): array
    {
        return [
            'name' => $this->name,
            'days' => $this->days,
            'hours' => $this->hours,
            'force' => $this->force,
            'force_create' => $this->force_create,
            'force_man_up' => $this->force_man_up,
            'force_woman_up' => $this->force_woman_up,
            'force_woman_special_up' => $this->force_woman_special_up,
            'force_woman_man_allowed' => $this->force_woman_man_allowed,
            'force_man_first_up' => $this->force_man_first_up,
            'force_woman_first_up' => $this->force_woman_first_up,
            'force_man_min' => $this->force_man_min,
            'force_woman_min' => $this->force_woman_min,
        ];
    }

    public static function fromArchive(array $archive): void
    {
        static::create($archive);
    }
}
