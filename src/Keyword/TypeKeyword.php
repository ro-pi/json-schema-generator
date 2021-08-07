<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator\Keyword;

use Ropi\JsonSchemaGenerator\Context\RecordContext;

class TypeKeyword implements KeywordInterface
{
    /**
     * @throws \Ropi\JsonSchemaGenerator\Context\Exception\UnsupportedInstanceTypeException
     */
    public function recordInstance(RecordContext $context): void
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

        if (!is_array($schema->type)) {
            $schema->type = [$schema->type, $instanceType];
            return;
        }

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
    }

    protected function isNumericType(string $type): bool
    {
        return $type === 'integer' || $type === 'number';
    }
}