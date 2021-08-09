<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator\Keyword;

use Ropi\JsonSchemaGenerator\Context\RecordContext;

class SchemaKeyword implements KeywordInterface
{
    public function recordInstance(RecordContext $context): void
    {
        if ($context->getCurrentSchemaLevel() !== 0) {
            return;
        }

        $schema = $context->getCurrentSchema();
        if (property_exists($schema, '$schema')) {
            return;
        }

        $schema->{'$schema'} = $context->config->draft->getUri();
    }
}