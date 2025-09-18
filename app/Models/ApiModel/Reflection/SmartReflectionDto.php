<?php

namespace App\Models\ApiModel\Reflection;

class SmartReflectionDto
{
    public array $construct;
    public array $fields;
    public array $attributes;
    public array $smartArrays;
    public \stdClass $smart;

    /** just for IDE syntax highlight
     *
     * @param string $fieldName
     * @return SmartReflection
     */
    public function smart($fieldName)
    {
        return $this->smart->$fieldName;
    }
}
