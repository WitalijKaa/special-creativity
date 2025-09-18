<?php

namespace App\Models\ApiModel\Reflection;

use App\Models\ApiModel\Attributes\AttrFieldHardName;
use App\Models\ApiModel\Attributes\AttrFieldPreventToArrayOnNull;
use App\Models\ApiModel\Attributes\TimeFormats\AttrAbstractTimeFormat;
use App\Models\ApiModel\Attributes\TimeZones\AttrAbstractTimeZone;
use App\Models\ApiModel\Attributes\TimeZones\AttrCaliforniaTimeZone;
use App\Models\ApiModel\BaseModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SmartReflection
{
    public bool $allowsNull = false;

    private const string DEFAULT_TIME_ZONE_ATTR = AttrCaliforniaTimeZone::class;

    public string $name;
    public ?string $hardName = null;
    public bool $preventToArrayOnNull = false;
    public bool $isClassForeign;
    public string $className;

    public bool $isCollection = false;
    public array $classesOfCollection;

    public bool $isCarbon = false;
    public string|array $carbonParseFormat;
    public string $carbonParseTimeZone; // default is 'America/Los_Angeles'

    public function __construct(public \ReflectionProperty $model)
    {
        $modelClass = $this->model->class;
        /** @var \App\Models\ApiModel\BaseModel $modelClass */
        $this->name = $this->model->name;
        $refType = $this->model->getType();

        if ($refAttrs = $this->model->getAttributes()) {
            if (static::findHasAttribute($refAttrs, AttrFieldHardName::class) &&
                ($hardName = $modelClass::findHardName($this->name))) {
                $this->hardName = $hardName;
            }

            $this->preventToArrayOnNull = static::findHasAttribute($refAttrs, AttrFieldPreventToArrayOnNull::class);
        }

        if ($refType instanceof \ReflectionNamedType) {
            $this->allowsNull = $refType->allowsNull();

            if ($refType->isBuiltin()) {
                return;
            }

            if ($refType->getName() == Collection::class && $refAttrs) {

                if ($this->allowsNull) {
                    throw new \UnexpectedValueException($this->model->class . ' # ' . $this->model->name . ' collection should not be nullable type');
                }

                $this->isCollection = true;
                $this->classesOfCollection = [$refAttrs[0]->getName()];
                $this->isClassForeign = !is_subclass_of($this->classesOfCollection[0], BaseModel::class);
            }
            else if ($refType->getName() == Carbon::class && $refAttrs) {
                $this->isCarbon = true;

                $classes = $this->findSubClassesFromReflectionAttributes($refAttrs, [
                    'classFormat' => AttrAbstractTimeFormat::class,
                    'classTimeZone' => AttrAbstractTimeZone::class,
                ]);
                extract($classes);

                if (empty($classFormat)) {
                    throw new \UnexpectedValueException($this->model->class . ' # ' . $this->model->name . ' empty $classFormat');
                }

                /** @var $classFormat \App\Models\ApiModel\Attributes\TimeFormats\AttrAbstractTimeFormat */
                $this->carbonParseFormat = $classFormat::invoke();

                $classTimeZone = empty($classTimeZone) ? self::DEFAULT_TIME_ZONE_ATTR : $classTimeZone;
                /** @var $classTimeZone \App\Models\ApiModel\Attributes\TimeZones\AttrAbstractTimeZone */
                $this->carbonParseTimeZone = $classTimeZone::invoke();
            }
            else {
                $this->className = $refType->getName();
                $this->isClassForeign = !is_subclass_of($this->className, BaseModel::class);
            }
        } else if ($refType instanceof \ReflectionUnionType || $refType instanceof \ReflectionIntersectionType) {
            foreach ($refType->getTypes() as $refSubType) {
                if (!method_exists($refSubType, 'allowsNull')) {
                    continue;
                }

                if ($refSubType->allowsNull()) {
                    $this->allowsNull = true;
                    return;
                }
            }
        }
    }

    private function findHasAttribute(array $refAttrs, string $attr): bool
    {
        foreach ($refAttrs as $refAttr)
        {
            if ($refAttr->getName() == $attr) {
                return true;
            }
        }
        return false;
    }

    private function findSubClassesFromReflectionAttributes(array $refAttrs, array $config): array
    {
        $return = [];
        foreach ($refAttrs as $refAttr)
        {
            /** @var \ReflectionAttribute $refAttr */
            foreach ($config as $returnProperty => $baseClass) {

                if (empty($return[$returnProperty]) && is_subclass_of($refAttr->getName(), $baseClass)) {
                    $return[$returnProperty] = $refAttr->getName();
                    continue 2;
                }
            }
        }
        return $return;
    }

    public function getCastClass(): ?string
    {
        if (!empty($this->className) && !$this->isCollection) {
            return $this->className;
        } else if ($this->isCollection && !empty($this->classesOfCollection)) {
            return $this->classesOfCollection[0];
        }
        return null;
    }

    public static function getProps(string $class): SmartReflectionDto
    {
        $R = new \ReflectionClass($class);
        $propsPublic = array_map(fn (\ReflectionProperty $prop) => new SmartReflection($prop), $R->getProperties(\ReflectionProperty::IS_PUBLIC));
        $propsStatic = array_map(fn (\ReflectionProperty $prop) => new SmartReflection($prop), $R->getProperties(\ReflectionProperty::IS_STATIC));

        $propsPublicStr = array_map(fn (SmartReflection $smart) => (string)$smart, $propsPublic);
        $propsStaticStr = array_map(fn (SmartReflection $smart) => (string)$smart, $propsStatic);
        $propsStr = array_values(array_diff($propsPublicStr, $propsStaticStr));

        $propsSmart = array_values(array_filter($propsPublic, fn (SmartReflection $smart) => in_array((string)$smart, $propsStr)));

        $propsSmartObj = new \stdClass();
        foreach ($propsSmart as $S) {
            $name = (string)$S;
            $propsSmartObj->$name = $S;
        }

        $construct = [];
        if ($R->getConstructor()?->getParameters()) {
            foreach ($R->getConstructor()->getParameters() as $param) {
                $construct[] = $param->name;
            }
        }

        $dto = new SmartReflectionDto();
        $dto->construct = $construct;
        $dto->fields = $propsStr;
        $methods = $R->getMethods();
        $dto->attributes = static::attributesReflection($methods);
        $dto->smartArrays = static::smartArraysReflection($methods);
        $dto->smart = $propsSmartObj;

        return $dto;
    }

    private static function attributesReflection(array $refMethods): array
    {
        $refMethods = array_filter($refMethods, fn (\ReflectionMethod $method) =>
            !($method->getModifiers() & \ReflectionMethod::IS_STATIC) &&
            ($method->getModifiers() & \ReflectionMethod::IS_PUBLIC) &&
            str_starts_with($method->name, 'get') &&
            str_ends_with($method->name, 'Attribute')
        );

        $attributes = [];

        foreach ($refMethods as $method) {
            /** @var \ReflectionMethod $method */
            $name = substr($method->name, strlen('get'), -1 * strlen('Attribute'));
            $attributes[Str::camel($name)] = $method->name;
            $attributes[Str::snake(Str::camel($name))] = $method->name;
        }
        return $attributes;
    }

    private static function smartArraysReflection(array $refMethods): array
    {
        $smartArrStrLength = strlen('smartArray');
        $refMethods = array_filter($refMethods, fn (\ReflectionMethod $method) =>
            ($method->getModifiers() & \ReflectionMethod::IS_PUBLIC) &&
            ($method->getModifiers() & \ReflectionMethod::IS_STATIC) &&
            str_starts_with($method->name, 'smartArray') &&
            strlen($method->name) > $smartArrStrLength
        );

        $smartArrays = [];

        foreach ($refMethods as $method) {
            /** @var \ReflectionMethod $method */
            $key = ucfirst(substr($method->name, $smartArrStrLength));
            $smartArrays[$key] = call_user_func($method->class . '::' . $method->name);
        }
        return $smartArrays;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
