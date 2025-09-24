<?php

namespace App\Models\ApiModel;

use App\Models\ApiModel\Attributes\AttrFieldHardName;
use App\Models\ApiModel\Attributes\TimeFormats\AttrExampleTimeFormat;
use App\Models\ApiModel\Attributes\TimeFormats\AttrYmdHisTimeFormat;
use App\Models\ApiModel\Attributes\TimeZones\AttrCaliforniaTimeZone;
use App\Models\ApiModel\Attributes\TimeZones\AttrUtcTimeZone;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

#[\Attribute]
class ExampleModel extends BaseModel
{
    #[AttrFieldHardName]
    public string $asdf;
    public string|float $stringFloat;
    public null|string|int|false $mixed;
    public ?string $stroka;
    public ?int $cislo;
    public ?bool $trigger;
    public ?array $massiv;

    public ExampleModel $model;

    #[ExampleModel(42)]
    public Collection $kolekcija;

    #[AttrYmdHisTimeFormat, AttrCaliforniaTimeZone]
    public Carbon $casCA;
    #[AttrExampleTimeFormat, AttrUtcTimeZone]
    public ?Carbon $casUTC;
    #[AttrExampleTimeFormat]
    public Carbon $casTZ;

    public static string $shouldNotBeSet;
    protected string $protected = 'default value';

    protected static array $hardNames = ['qwer' => 'asdf'];

    public function __construct(protected readonly int $constructed)
    {
        $this->protected = 42 == $this->constructed ? 'changed value' : $this->protected;
    }

    public function getProtectedProp(): string
    {
        return $this->protected; // for unit tests
    }

    public function getViaGetterAttribute()
    {
        return 42;
    }

    public static function smartArrayExample(): array
    {
        return ['cislo', 'stroka', 'via_getter'];
    }
}
