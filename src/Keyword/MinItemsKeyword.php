<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator\Keyword;

use Ropi\JsonSchemaGenerator\Context\RecordContext;

class MinItemsKeyword implements KeywordInterface
{
    public function recordInstance(RecordContext $context): void
    {
        $instance = $context->getCurrentInstance();
        if (!is_array($instance)) {
            return;
        }

        $schema = $context->getCurrentSchema();
        $count = count($instance);

        if (!isset($schema->minItems)) {
            $schema->minItems = $count;
            return;
        }

        $schema->minItems = min($count, $schema->minItems);
    }
}