<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator\Keyword;

use Ropi\JsonSchemaGenerator\Context\RecordContext;

class MinLengthKeyword implements KeywordInterface
{
    public function recordInstance(RecordContext $context): void
    {
        $instance = $context->getCurrentInstance();
        if (!is_string($instance)) {
            return;
        }

        $schema = $context->getCurrentSchema();
        $length = mb_strlen($instance, 'UTF-8');

        if (!isset($schema->minLength)) {
            $schema->minLength = $length;
            return;
        }

        $schema->minLength = min($length, $schema->minLength);
    }
}