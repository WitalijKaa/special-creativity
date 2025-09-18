<?php

namespace App\Models\Work;

use App\Models\Inteface\JsonArchivableInterface;
use App\Models\Person\PersonEvent;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string $name
 * @property int $begin
 * @property int $end
 * @property ?int $capacity
 * @property ?int $consumers
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Work query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Work where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Work whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Work whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Work whereBegin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Work whereEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Work whereCapacity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Work whereConsumers($value)
 *
 * @property-read ?float $consuming_of_person
 * @property-read ?float $consuming_days_per_year
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|PersonEvent[] $events
 *
 * @mixin \Eloquent
 */
class Work extends \Eloquent implements JsonArchivableInterface
{
    public const string TABLE_NAME = DB . '_work';
    protected $table = self::TABLE_NAME;

    public WorkCalculationsDto $calculations;

    public function calculate()
    {
        $this->calculations = LifeWork::calculateWork($this);
    }

    public function percent(float $years): float
    {
        return round($years / $this->calculations->workYears * 100.0, 2);
    }

    public function percentByDays(float $days): float
    {
        return round($days / $this->calculations->days * 100.0, 2);
    }

    public function getConsumingOfPersonAttribute() // consuming_of_person
    {
        return !$this->consumers ? 0.0 : round($this->calculations->workYears / $this->consumers, 2);
    }

    public function getConsumingDaysPerYearAttribute() // consuming_days_per_year
    {
        return !$this->consumers ? 0.0 : round($this->calculations->days / count($this->calculations->worksPerYear) / $this->consumers, 2);
    }

    public function archive(): array
    {
        $return = [
            'name' => $this->name,
            'begin' => $this->begin,
            'end' => $this->end,
        ];
        if ($this->capacity) { $return['capacity'] = $this->capacity; }
        if ($this->consumers) { $return['consumers'] = $this->consumers; }
        return $return;
    }

    public $timestamps = false;
    protected $guarded = ['id'];
    public function events(): HasMany { return $this->hasMany(PersonEvent::class, 'work_id', 'id'); }
    protected function casts(): array
    {
        return [
            'begin' => 'integer',
            'end' => 'integer',
            'capacity' => 'integer',
            'consumers' => 'integer',
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
