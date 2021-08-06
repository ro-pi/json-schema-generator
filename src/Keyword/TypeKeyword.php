<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator\Keyword;

use Ropi\JsonSchemaGenerator\GenerationContext\GenerationContext;
use Ropi\JsonSchemaGenerator\Keyword\Exception\InterruptSchemaMutationException;

class TypeKeyword implements KeywordInterface
{
    /**
     * @throws InterruptSchemaMutationException
     * @throws \Ropi\JsonSchemaGenerator\GenerationContext\Exception\UnsupportedInstanceTypeException
     */
    public function recordInstance(GenerationContext $context): void
    {
        $schema = $context->getCurrentSchema();
        $instanceType = $context->getCurrentInstanceJsonSchemaType();

        if (!isset($schema->type) && !isset($schema->anyOf)) {
            $schema->type = $context->getCurrentInstanceJsonSchemaType();
            return;
        }

        if (isset($schema->type) && is_string($schema->type)) {
            if ($schema->type === $instanceType) {
                return;
            }

            if ($this->isNumericType($schema->type) && $this->isNumericType($instanceType)) {
                $schema->type = 'number';
                return;
            }
        }

        if ($context->config->multipleTypesToAnyOf) {
            if (!isset($schema->anyOf)) {
                // Move current schema to anyOf

                $firstAnyOfSchema = clone $schema;
                unset($firstAnyOfSchema->{'$schema'});

                $anyOf = [$firstAnyOfSchema];

                foreach ($schema as $keywordName => $keywordValue) {
                    if ($keywordName === '$schema') {
                        continue;
                    }

                    unset($schema->$keywordName);
                }

                $schema->anyOf = $anyOf;
            }

            $targetSchema = $this->resolveSchemaForType($schema, $instanceType);

            $context->pushSchema($targetSchema);
            $context->config->draft->recordInstance($context);
            $context->popSchema();

            throw new InterruptSchemaMutationException();
        }

        if (is_array($schema->type)) {
            foreach ($schema->type as $typeIndex => $type) {
                if ($type === $instanceType) {
                    return;
                }

                if ($this->isNumericType($type) && $this->isNumericType($instanceType)) {
                    $schema->type[$typeIndex] = 'number';
                    return;
                }
            }

            $schema->type[] = $instanceType;
            return;
        }

        $schema->type = [$schema->type, $instanceType];
    }

    protected function isNumericType(string $type): bool
    {
        return $type === 'integer' || $type === 'number';
    }

    protected function resolveSchemaForType(object $schema, string $type): object
    {
        foreach ($schema->anyOf as $anyOf) {
            if ($anyOf->type === $type) {
                return $anyOf;
            }

            if ($this->isNumericType($anyOf->type) && $this->isNumericType($type)) {
                $anyOf->type = 'number';
                return $anyOf;
            }
        }

        $targetSchema = new \stdClass();
        $targetSchema->type = $type;
        $schema->anyOf[] = $targetSchema;

        return $targetSchema;
    }
}