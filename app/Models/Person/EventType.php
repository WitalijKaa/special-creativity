<?php

namespace App\Models\Person;

use App\Models\Collection\PersonEventCollection;
use App\Models\Inteface\JsonArchivableInterface;
use App\Models\View\EventView;

/**
 * @property int $id
 * @property string $name
 * @property boolean $is_honor
 * @property boolean $is_relation
 * @property boolean $is_work
 * @property boolean $is_slave
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventType whereIsHonor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventType whereIsRelation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventType whereIsWork($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventType whereIsSlave($value)
 * @method \Illuminate\Database\Eloquent\Builder<static>|EventType whereIsHonor($value)
 * @method \Illuminate\Database\Eloquent\Builder<static>|EventType whereIsRelation($value)
 * @method \Illuminate\Database\Eloquent\Builder<static>|EventType whereIsWork($value)
 * @method \Illuminate\Database\Eloquent\Builder<static>|EventType whereIsSlave($value)
 *
 * @mixin \Eloquent
 */
class EventType extends \Eloquent implements JsonArchivableInterface
{
    public const int DEEP_LOVE = 1;
    public const int ONCE_LOVE = 2;
    public const int EMPTY_LOVE = 3;
    public const int DIRTY_LOVE = 4;
    public const int HOLY_LIFE = 5;

    public const string TABLE_NAME = DB . '_type_event';
    protected $table = self::TABLE_NAME;

    public function archive(): array
    {
        $return = ['name' => $this->name];
        if ($this->is_honor) { $return['is_honor'] = 1; }
        if ($this->is_relation) { $return['is_relation'] = 1; }
        if ($this->is_work) { $return['is_work'] = 1; }
        if ($this->is_slave) { $return['is_slave'] = 1; }
        return $return;
    }

    public $timestamps = false;
    protected $guarded = ['id'];
    protected function casts(): array
    {
        return [
            'is_honor' => 'boolean',
            'is_relation' => 'boolean',
            'is_work' => 'boolean',
            'is_slave' => 'boolean',
        ];
    }

    public static function selectOptions(): array
    {
        $vEvent = new EventView();
        return PersonEventCollection::typesSorted()
            ->map(fn (self $model) => ['opt' => $model->id, 'lbl' => $model->name, 'style' => $vEvent->backColorType($model)])
            ->toArray();
    }
}
