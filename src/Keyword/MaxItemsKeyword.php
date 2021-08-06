<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator\Keyword;

use Ropi\JsonSchemaGenerator\GenerationContext\GenerationContext;

class MaxItemsKeyword implements KeywordInterface
{
    public function recordInstance(GenerationContext $context): void
    {
        $instance = $context->getCurrentInstance();
        if (!is_array($instance)) {
            return;
        }

        $schema = $context->getCurrentSchema();
        $count = count($instance);

        if (!isset($schema->maxItems)) {
            $schema->maxItems = $count;
            return;
        }

        $schema->maxItems = max($count, $schema->maxItems);
    }
}