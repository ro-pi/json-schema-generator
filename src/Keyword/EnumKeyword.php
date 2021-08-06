<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator\Keyword;

use Ropi\JsonSchemaGenerator\GenerationContext\GenerationContext;

class EnumKeyword implements GeneratingKeywordInterface
{
    use SchemaDataMapTrait;

    public function recordInstance(GenerationContext $context): void
    {
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
        if (!$enum) {
            return;
        }

        $enum[$context->getCurrentInstanceHash()] = $instance;
        if (count($enum) > $context->config->maxEnumSize) {
            $this->setSchemaData($schema, false);
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