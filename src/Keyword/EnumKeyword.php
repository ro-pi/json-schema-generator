<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator\Keyword;

use Ropi\JsonSchemaGenerator\Context\RecordContext;

class EnumKeyword implements GeneratingKeywordInterface
{
    use SchemaDataMapTrait;

    public function recordInstance(RecordContext $context): void
    {
        if (!$context->config->maxEnumSize) {
            return;
        }

        $instance = $context->getCurrentInstance();
        if (!is_scalar($instance)) {
            return;
        }

        $schema = $context->getCurrentSchema();
        if (!$this->hasSchemaData($schema)) {
            $this->setSchemaData($schema, [$context->getCurrentInstanceHash() => $instance]);
            return;
        }

        $enum = $this->getSchemaData($schema);
        if (!is_array($enum)) {
            return;
        }

        $enum[$context->getCurrentInstanceHash()] = $instance;
        if (count($enum) > $context->config->maxEnumSize) {
            $this->setSchemaData($schema, true);
            return;
        }

        $this->setSchemaData($schema, $enum);
    }

    public function generateSchema(): void
    {
        foreach ($this->getSchemaDataMap() as $schema => $enum) {
            if (is_array($enum)) {
                $schema->enum = array_values($enum);
                unset($schema->minimum);
                unset($schema->maximum);
                unset($schema->minLength);
                unset($schema->maxLength);
                unset($schema->examples);
                unset($schema->estimatedCardinality);
            }
        }
    }
}