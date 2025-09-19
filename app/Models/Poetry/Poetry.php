<?php

namespace App\Models\Poetry;

use App\Models\Inteface\JsonArchivableInterface;
use App\Models\Inteface\PoetryInterface;
use App\Models\Person\Person;
use App\Models\World\Life;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $person_id
 * @property int $life_id
 * @property int $begin
 * @property int $end
 * @property int $ix_text
 * @property ?string $ai
 * @property string $text
 * @property string $lang
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poetry query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poetry first($columns = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poetry orderBy($column, $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poetry where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poetry whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poetry wherePersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poetry whereLifeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poetry whereBegin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poetry whereEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poetry whereIxText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poetry whereAi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poetry whereLang($value)
 *
 * @property-read Person $person
 * @property-read Life $life
 *
 * @mixin \Eloquent
 */
class Poetry extends \Eloquent implements JsonArchivableInterface, PoetryInterface
{
    public const string TABLE_NAME = DB . '_poetry';
    protected $table = self::TABLE_NAME;

    public function archive(): array
    {
        return [
            'export' => 'poetry',
            'export_id' => $this->ix_text,
            'life_id' => $this->life_id,

            'text' => $this->text,
            'lang' => $this->lang,
            'ai' => $this->ai,
            'begin' => $this->begin,
            'end' => $this->end,
        ];
    }

    public function text(): string { return $this->text; }

    public function translation(string $text, string $lang, string $ai): static
    {
        $model = new static();
        $model->text = $text;
        $model->lang = $lang;
        $model->ai = $ai;
        $model->life_id = $this->life_id;
        $model->person_id = $this->person_id;
        $model->ix_text = $this->ix_text;
        $model->begin = $this->begin;
        $model->end = $this->end;
        return $model;
    }

    public $timestamps = false;
    protected $guarded = ['id'];
    public function person(): HasOne { return $this->hasOne(Person::class, 'id', 'person_id'); }
    public function life(): HasOne { return $this->hasOne(Life::class, 'id', 'life_id'); }
    protected function casts(): array
    {
        return [
            'begin' => 'integer',
            'end' => 'integer',
            'ix_text' => 'integer',
            'person_id' => 'integer',
            'life_id' => 'integer',
        ];
    }
}
