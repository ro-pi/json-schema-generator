<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator\Keyword;

use Ropi\JsonSchemaGenerator\Context\RecordContext;

class MinimumKeyword implements KeywordInterface
{
    public function recordInstance(RecordContext $context): void
    {
        $instance = $context->getCurrentInstance();
        if (!is_int($instance) && !is_float($instance)) {
            return;
        }

        $schema = $context->getCurrentSchema();
        if (!isset($schema->minimum)) {
            $schema->minimum = $instance;
            return;
        }

        $schema->minimum = min($instance, $schema->minimum);
    }
}