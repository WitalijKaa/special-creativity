<?php

namespace App\Models\World;

use App\Models\Person\Person;
use App\Models\Person\PersonEventSynthetic;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string $name
 * @property int $begin
 * @property int $end
 * @property ?int $capacity
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Work query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Work where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Work whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Work whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Work whereBegin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Work whereEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Work whereCapacity($value)
 *
 * @mixin \Eloquent
 */
class Work extends \Eloquent
{
    public const string TABLE_NAME = DB . '_work';
    protected $table = self::TABLE_NAME;

    public function archive(): array
    {
        $return = [
            'name' => $this->name,
            'begin' => $this->begin,
            'end' => $this->end,
        ];
        if ($this->capacity) { $return['capacity'] = $this->capacity; }
        return $return;
    }

    public $timestamps = false;
    protected $guarded = ['id'];
    protected function casts(): array
    {
        return [
            'begin' => 'integer',
            'end' => 'integer',
            'capacity' => 'integer',
        ];
    }

    public static function selectOptions(): array
    {
        return static::orderBy('id')
            ->get()
            ->map(fn (self $model) => ['opt' => $model->id, 'lbl' => $model->name])
            ->toArray();
    }

    public static function selectSpecificOptions(Collection $clt): array
    {
        return array_merge(
            [['opt' => '0', 'lbl' => 'NO WORK']],
            $clt->map(fn (self $model) => ['opt' => $model->id, 'lbl' => $model->name])->toArray()
        );
    }
}
