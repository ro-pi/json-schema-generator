<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator\Keyword;

use Ropi\JsonSchemaGenerator\GenerationContext\GenerationContext;
use Ropi\JsonSchemaGenerator\Keyword\Exception\KeywordSchemaMutationException;

class TypeKeyword implements KeywordInterface
{
    public function mutateSchema(GenerationContext $context): void
    {
        $context->getCurrentSchema()->type = $this->mapJsonSchemaType($context->getCurrentInstance());
    }

    /**
     * @throws KeywordSchemaMutationException
     */
    protected function mapJsonSchemaType(mixed $value): string
    {
        return match (true) {
            is_object($value) => 'object',
            is_array($value) => 'array',
            is_string($value) => 'string',
            is_int($value) => 'integer',
            is_float($value) => 'float',
            is_bool($value) => 'boolean',
            is_null($value) => 'null',
            default => throw new KeywordSchemaMutationException(
                'Can not map value with type "'
                . gettype($value)
                . '" to a suitable JSON Schema type',
                1628197539
            )
        };
    }
}