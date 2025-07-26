<?php

namespace App\Models\World;

use App\Models\Collection\PersonCollection;
use App\Models\Person\PersonEvent;
use App\Models\Work\PersonOfWorkDto;
use App\Models\Work\WorkCalculationsDto;
use App\Models\Work\YearOfWorkDto;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
 * @property-read \Illuminate\Database\Eloquent\Collection|PersonEvent[] $events
 *
 * @mixin \Eloquent
 */
class Work extends \Eloquent
{
    public const string TABLE_NAME = DB . '_work';
    protected $table = self::TABLE_NAME;

    public WorkCalculationsDto $calculations;

    public function calculate()
    {
        $this->calculations = new WorkCalculationsDto();
        $this->calculations->workers = new PersonCollection();

        for ($year = $this->begin; $year <= $this->end; $year++) {
            $workers = new PersonCollection();
            $yearEvents = $this->events->filter(fn (PersonEvent $event) => $event->begin <= $year && $event->end >= $year)
                ->each(fn (PersonEvent $event) => $workers->pushUniqueWorkers($year, $event));

            if ($yearEvents->count()) {
                $yearDto = new YearOfWorkDto($workers);
                foreach ($yearDto->workers as $worker) {
                    $yearDto->days += $worker->days;
                }
                if (!$yearDto->days) {
                    continue;
                }
                $yearDto->workers->each(fn (PersonOfWorkDto $workerDto) => $this->calculations->workers->pushUniqueWorker($workerDto));
                $this->calculations->days += $yearDto->days;
                $this->calculations->worksPerYear[$year] = $yearDto;

                if (empty($this->calculations->begin)) {
                    $this->calculations->begin = $year;
                }
                $this->calculations->end = $year;
            }
        }
        $this->calculations->workYears = number_format($this->calculations->days / WORK_DAYS, 2);
    }

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
    public function events(): HasMany { return $this->hasMany(PersonEvent::class, 'work_id', 'id'); }
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
