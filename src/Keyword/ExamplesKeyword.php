<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator\Keyword;

use Ropi\JsonSchemaGenerator\GenerationContext\GenerationContext;

class ExamplesKeyword implements KeywordInterface
{
    public function mutateSchema(GenerationContext $context): void
    {
        $instance = $context->getCurrentInstance();
        if (!is_scalar($instance)) {
            return;
        }

        $schema = $context->getCurrentSchema();
        if (!isset($schema->examples)) {
            $schema->examples = [];
        }

        $numExamples = count($schema->examples);
        if ($numExamples >= $context->config->maxExampleValues) {
            return;
        }

        $schema->examples[crc32((string) $instance)] = $instance;
    }
}