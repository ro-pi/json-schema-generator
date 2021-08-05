<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator\Schema;

class ArraySchema extends Schema
{
    public string $type = 'array';
    public array $items = [];
    public int $minItems;
    public int $maxItems;

    public function __construct(array $firstValue)
    {
        $this->minItems = count($firstValue);
        $this->maxItems = count($firstValue);
    }

    public function recordValue(array $value): void
    {
        $this->minItems = min(count($value), $this->minItems);
        $this->maxItems = max(count($value), $this->maxItems);
    }
}
