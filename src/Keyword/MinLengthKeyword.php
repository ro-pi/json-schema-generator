<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator\Keyword;

use Ropi\JsonSchemaGenerator\GenerationContext\GenerationContext;

class MinLengthKeyword implements KeywordInterface
{
    public function mutateSchema(GenerationContext $context): void
    {
        $instance = $context->getCurrentInstance();
        if (!is_string($instance)) {
            return;
        }

        $schema = $context->getCurrentSchema();
        $length = mb_strlen($instance, 'UTF-8');

        if (isset($schema->minLength)) {
            $schema->minLength = min($length, $schema->minLength);
        } else {
            $schema->minLength = $length;
        }
    }
}