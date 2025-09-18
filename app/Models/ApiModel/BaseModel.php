<?php

namespace App\Models\ApiModel;

use App\Models\ApiModel\Attributes\TimeFormats\AttrYmdTimeFormat;
use App\Models\ApiModel\Reflection\SmartReflection;
use App\Models\ApiModel\Reflection\SmartReflectionDto;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use JsonSerializable;

abstract class BaseModel implements Arrayable, JsonSerializable {

//    # in __construct u can use only protected and public props
//    # all props from __construct are in toArray()

//    # AttrFieldHardName and static::$hardNames are for renaming props for json from php->fields

//    # to CAST model
//    public ModelCase $propModel;

//    # to CAST collection of models
//    # dont use ?Collection nullable
//    /** @var \Illuminate\Support\Collection|\Packages\Some\ModelCase[] */
//    #[ModelCase]
//    public Collection $collectionOfModelCases;

//    # to CAST Carbon
//    # STRICT use Illuminate\Support\Carbon
//    # default timezone to parse time is AttrCaliforniaTimeZone (if string does not provide TZ)
//    #[AttrExampleTimeFormat]
//    public Carbon $carbonModel;

    protected ?array $originalArr = null;
    protected array $originalTimeArr = [];
    protected static bool $modelCastsSimplified = false;

    protected static array $hardNames = []; // json.name => phpField

    private static array $reflectionsRepository; // reflection data

    private bool $isApiResourceModel = false;

    public final static function byArr(array $item): static
    {
        static::findReflection();
        $model = static::createModelToFill($item);
        return $model->fillSelf($item);
    }

    protected final function fillSelf(array $item): static
    {
        static::findReflection();
        static::beforeFill($item);
        $self = static::createAndFillByArray($item, $this);
        $self->afterFill();
        return $self;
    }

    protected static function beforeFill(array &$item): void
    {
    }

    protected function afterFill(): void
    {
    }

    private static function ref(): SmartReflectionDto
    {
        return static::$reflectionsRepository[static::class];
    }

    private static function createAndFillByArray(array $item, $model = null): static
    {
        $model = empty($model) || !$model instanceof static ? static::createModelToFill($item) : $model;
        if (static::$modelCastsSimplified) {
            $model->originalArr = $item;
        }
        $handledFields = static::ref()->construct;

        foreach ($item as $field => $value) {
            if (array_key_exists($field, static::$hardNames)) {
                $field = static::$hardNames[$field];
            }

            if (!in_array($field, static::ref()->fields) || in_array($field, static::ref()->construct)) {
                continue;
            }
            $handledFields[] = $field;

            $smartProp = static::ref()->smart($field);

            if (is_null($value)) {
                if ($smartProp->allowsNull) {
                    $model->$field = null;
                }
                continue;
            }

            if ((empty($value) || !is_array($value)) && $smartProp->isCollection) {
                $model->$field = new Collection();
                continue;
            }

            $class = $smartProp->getCastClass();
            /** @var string|BaseModel|mixed $class */

            if ($smartProp->isCollection) {
                $model->$field = new Collection();
                foreach ($value as $subItem) {
                    $model->$field->add($smartProp->isClassForeign ? new $class(...$value) : $class::byArr($subItem));
                }
            }
            else if ($smartProp->isCarbon) {
                $carbonFormats = is_array($smartProp->carbonParseFormat) ? $smartProp->carbonParseFormat : [$smartProp->carbonParseFormat];
                foreach ($carbonFormats as $ix => $carbonFormat) {
                    $isLastVariant = count($carbonFormats) == $ix + 1;
                    try {
                        $model->$field = Carbon::createFromFormat($carbonFormat, $value);
                        break;
                    } catch (InvalidFormatException $ex) {
                        if ($isLastVariant) {
                            throw $ex;
                        }
                    }
                }
                $model->$field->setTimezone($smartProp->carbonParseTimeZone);

                if (AttrYmdTimeFormat::invoke() === $carbonFormat) {
                    $model->$field->setTime(0, 0, 0, 0);
                }
                $model->originalTimeArr[$field] = $value;
            }
            else if ($class && is_array($value)) {
                $model->$field = $smartProp->isClassForeign ? new $class(...$value) : $class::byArr($value);
            }
            else {
                $model->$field = $value;
            }
        }

        static::fillNullableFields($model, $handledFields);

        return $model;
    }

    private static function createModelToFill(array $item): static
    {
        $construct = [];
        foreach (static::ref()->construct as $param) {
            if (array_key_exists($param, $item)) {
                $construct[$param] = $item[$param];
            }
        }

        return $construct ? new static(...$construct) : new static();
    }

    private static function fillNullableFields($model, array $handledFields): void
    {
        /** @var static $model */

        foreach (static::ref()->fields as $field) {
            if (!empty($model->$field) || in_array($field, $handledFields)) {
                continue;
            }

            $smartProp = static::ref()->smart($field);

            if ($smartProp->isCollection) {
                $model->$field = new Collection();
            } else if ($smartProp->allowsNull && !isset($model->$field)) {
                $model->$field = null;
            }
        }
    }

    private static function findReflection(): void
    {
        if (empty(static::$reflectionsRepository[static::class])) {
            static::$reflectionsRepository[static::class] = SmartReflection::getProps(static::class);
        }
    }

    public final function toArray(?string $smartName = null): array
    {
        static::findReflection();
        $return = [];

        $smartName = $smartName ? ucfirst($smartName) : null;
        $smartOnly = $smartName && array_key_exists($smartName, static::ref()->smartArrays) ? static::ref()->smartArrays[$smartName] : [];

        foreach (static::ref()->construct as $prop) {
            if ($smartOnly && !in_array($prop, $smartOnly)) {
                continue;
            }

            $return[$prop] = $this->$prop;
        }

        foreach (static::ref()->fields as $field) {

            if ($smartOnly && !in_array($field, $smartOnly)) {
                continue;
            }

            $smartProp = static::ref()->smart($field);

            if ($smartProp->preventToArrayOnNull && is_null($this->$field ?? null)) {
                continue;
            }

            $jsonName = !empty($smartProp->hardName) ? $smartProp->hardName : $field;

            if (!isset($this->$field)) {
                $return[$jsonName] = $smartProp->isCollection ? [] : null;
            }
            else if ($smartProp->isCollection) {
                $return[$jsonName] = [];
                foreach ($this->$field as $item) {
                    /** @var \Illuminate\Contracts\Support\Arrayable $item */
                    $return[$jsonName][] = $item->toArray($smartName);
                }
            }
            else if ($smartProp->isCarbon) {
                $carbon = $this->$field;
                /** @var \Illuminate\Support\Carbon $carbon */
                $return[$jsonName] = $carbon->avoidMutation()
                    ->setTimezone($this->isApiResourceModel ? config('app.timezone') : $smartProp->carbonParseTimeZone)
                    ->format($smartProp->carbonParseFormat);
            }
            else if (!empty($smartProp->className)) {
                $return[$jsonName] = $this->$field->toArray($smartName);
            }
            else {
                $return[$jsonName] = $this->$field;
            }
        }

        if ($smartOnly) {
            foreach ($smartOnly as $fieldName) {
                if (array_key_exists($fieldName, $return) || !$this->hasAttribute($fieldName)) {
                    continue;
                }
                $return[$fieldName] = $this->$fieldName;
            }
        }

        return $return;
    }

    protected function toArrayAndCreation(?string $smartName = null): array
    {
        return static::modifyArrByModel($this->originalArr, $this->toArray($smartName));
    }

    public function toAPI(?string $smartName = null): array
    {
        return $this->originalArr ? $this->toArrayAndCreation($smartName) : $this->toArray($smartName);
    }

    public function getOriginal(): array
    {
        return $this->originalArr ?: [];
    }

    public function toJsonStr(?string $smartName = null): string
    {
        return json_encode($this->toArray($smartName), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    public function toJsonStrAPI(?string $smartName = null): string
    {
        return json_encode($this->toAPI($smartName), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    private static function modifyArrByModel(array $original, array $model): array
    {
        $return = [];
        foreach ($original as $K => $V) {
            if (array_key_exists($K, $model)) {
                if (is_array($model[$K])) {
                    $originalItem = is_array($original[$K]) ? $original[$K] : [];
                    $return[$K] = static::modifyArrByModel($originalItem, $model[$K]);
                }
                else {
                    $return[$K] = $model[$K];
                }
            } else {
                $return[$K] = $V;
            }
        }
        foreach ($model as $K => $V) {
            if (!array_key_exists($K, $return)) {
                $return[$K] = $V;
            }
        }
        return $return;
    }

    public function only(array $only, ?string $smartName = null): array
    {
        $return = array_filter($this->toArray($smartName), function ($key) use ($only) {
            return in_array($key, $only, true);
        }, ARRAY_FILTER_USE_KEY);

        foreach ($only as $fieldName) {
            if (array_key_exists($fieldName, $return) || !$this->hasAttribute($fieldName)) {
                continue;
            }
            $return[$fieldName] = $this->$fieldName;
        }

        return $return;
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    public function __clone(): void
    {
        $this->fillSelf($this->toArray());
    }

    public function toApiResource(): static
    {
        $model = clone $this;
        foreach (static::ref()->fields as $field) {
            if (!empty($model->$field) && static::ref()->smart($field)->isCarbon) {
                $model->$field->setTimezone(config('app.timezone'));
            }
        }
        $model->isApiResourceModel = true;
        return $model;
    }

    public static function findHardName(string $fieldName): ?string
    {
        $hardName = array_search($fieldName, static::$hardNames);
        return is_string($hardName) && $hardName ? $hardName : null;
    }

    public function getOriginalTimeString(string $fieldName): ?string
    {
        return array_key_exists($fieldName, $this->originalTimeArr) ? $this->originalTimeArr[$fieldName] : null;
    }

    public function __get(string $name)
    {
        static::findReflection();
        if ($this->hasAttribute($name)) {
            $method = static::ref()->attributes[$name];
            return $this->$method();
        }
        throw new \ErrorException('Undefined property: ' . static::class . ' ::$' . $name);
    }

    public function __isset(string $name): bool
    {
        static::findReflection();
        return $this->hasAttribute($name) || isset($this->$name);
    }

    public function hasAttribute(string $name): bool
    {
        return array_key_exists($name, static::ref()->attributes);
    }
}
