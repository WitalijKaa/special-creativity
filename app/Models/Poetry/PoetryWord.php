<?php

namespace App\Models\Poetry;

use App\Models\Inteface\JsonArchivableInterface;

/**
 * @property int $id
 * @property string $word
 * @property string $definition
 * @property string $lang
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PoetryWord query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PoetryWord where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PoetryWord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PoetryWord whereWord($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PoetryWord whereDefinition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PoetryWord whereLang($value)
 *
 * @mixin \Eloquent
 */
class PoetryWord extends \Eloquent implements JsonArchivableInterface
{
    public const string TABLE_NAME = DB . '_poetry_word';
    protected $table = self::TABLE_NAME;

    public function archive(): array
    {
        return [
            'export' => 'poetry_word',

            'word' => $this->word,
            'definition' => $this->definition,
            'lang' => $this->lang,
        ];
    }

    public $timestamps = false;
    protected $guarded = ['id'];
}
