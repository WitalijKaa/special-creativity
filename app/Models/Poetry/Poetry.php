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
 * @property int $chapter
 * @property ?string $ai
 * @property string $text
 * @property int $ix_text
 * @property int $begin
 * @property int $end
 * @property string $lang
 * @property int $part
 * @property int $spectrum
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poetry query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poetry first($columns = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poetry orderBy($column, $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poetry where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poetry whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poetry wherePersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poetry whereLifeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poetry whereChapter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poetry whereAi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poetry whereBegin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poetry whereEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poetry whereIxText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poetry whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poetry wherePart($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poetry whereSpectrum($value)
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

    public const SPECTRUM_MAIN = 1;
    public const SPECTRUM_PHILOSOPHY = 2;

    public function archive(): array
    {
        return [
            'person' => $this->person->name,
            'life_type' => $this->life->type,

            'chapter' => $this->chapter,
            'ix_text' => $this->ix_text,
            'text' => $this->text,
            'lang' => $this->lang,
            'ai' => $this->ai,
            'part' => $this->part,
            'spectrum' => $this->spectrum,
            'begin' => $this->begin,
            'end' => $this->end,
        ];
    }

    public static function fromArchive(array $archive): void
    {
        $life = Life::getByPersonNameLifeTypeYears($archive['person'], $archive['life_type'], $archive['begin'], $archive['end']);
        $archive['person_id'] = $life->person_id;
        $archive['life_id'] = $life->id;

        static::create($archive);
    }

    public function text(): string { return $this->text; }

    public function translation(string $text, string $lang, string $ai): static
    {
        $model = new static();
        $model->chapter = $this->chapter;
        $model->part = $this->part;
        $model->spectrum = $this->spectrum;
        $model->text = trim($text);
        $model->lang = $lang;
        $model->ai = $ai;
        $model->life_id = $this->life_id;
        $model->person_id = $this->person_id;
        $model->ix_text = $this->ix_text;
        $model->begin = $this->begin;
        $model->end = $this->end;
        return $model;
    }

    public static function isEndingWord(string $word): bool
    {
        return str_contains($word, '.') ||
            str_contains($word, ',') ||
            str_contains($word, ':');
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
