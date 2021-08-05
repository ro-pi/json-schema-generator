<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator\Schema;

class ObjectSchema extends Schema
{
    public string $type = 'object';
    public bool $additionalProperties = false;
    public array $required;
    public array $properties = [];

    public function __construct(object $firstValue)
    {
        $this->required = array_keys(get_object_vars($firstValue));
    }

    public function recordValue(object $value)
    {
        foreach ($this->required as $propertyKey => $property) {
            if (!isset($value->{$property})) {
                unset($this->required[$propertyKey]);
            }
        }
    }
}
