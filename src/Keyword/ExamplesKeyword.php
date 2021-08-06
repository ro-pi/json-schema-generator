<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator\Keyword;

use Ropi\JsonSchemaGenerator\GenerationContext\GenerationContext;

class ExamplesKeyword implements GeneratingKeywordInterface
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

        $examples = $this->getSchemaData($schema);
        if (count($examples) >= $context->config->maxExampleValues) {
            return;
        }

        $examples[$context->getCurrentInstanceHash()] = $instance;

        $this->setSchemaData($schema, $examples);
    }

    public function generateSchema(): void
    {
        foreach ($this->getSchemaDataMap() as $schema => $examples) {
            $schema->examples = array_values($examples);
        }
    }
}