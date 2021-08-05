<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator\Schema;

class IntegerSchema extends BasicTypeSchema
{
    public string $type = 'integer';
    public int $minimum;
    public int $maximum;

    public function __construct(int $firstValue)
    {
        $this->minimum = $firstValue;
        $this->maximum = $firstValue;
    }

    public function recordValue(int $value): void
    {
        $this->minimum = min($value, $this->minimum);
        $this->maximum = max($value, $this->maximum);
    }
}
