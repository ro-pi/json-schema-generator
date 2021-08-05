<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator\Schema;

class StringSchema extends BasicTypeSchema
{
    public string $type = 'string';
    public int $minLength;
    public int $maxLength;

    public function __construct(string $firstValue)
    {
        $length = mb_strlen($firstValue, 'UTF-8');
        $this->minLength = $length;
        $this->maxLength = $length;
    }

    public function recordValue(string $value): void
    {
        $length = mb_strlen($value, 'UTF-8');
        $this->minLength = min($length, $this->minLength);
        $this->maxLength = max($length, $this->maxLength);
    }
}
