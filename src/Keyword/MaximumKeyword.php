<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator\Keyword;

use Ropi\JsonSchemaGenerator\Context\RecordContext;

class MaximumKeyword implements KeywordInterface
{
    public function recordInstance(RecordContext $context): void
    {
        $instance = $context->getCurrentInstance();
        if (!is_int($instance) && !is_float($instance)) {
            return;
        }

        $schema = $context->getCurrentSchema();
        if (!isset($schema->maximum)) {
            $schema->maximum = $instance;
            return;
        }

        $schema->maximum = max($instance, $schema->maximum);
    }
}