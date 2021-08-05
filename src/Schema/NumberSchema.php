<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator\Schema;

class NumberSchema extends BasicTypeSchema
{
    public string $type = 'number';
    public float $minimum;
    public float $maximum;

    public function __construct(float $firstValue)
    {
        $this->minimum = $firstValue;
        $this->maximum = $firstValue;
    }

    public function recordValue(float $value): void
    {
        $this->minimum = min($value, $this->minimum);
        $this->maximum = max($value, $this->maximum);
    }
}
