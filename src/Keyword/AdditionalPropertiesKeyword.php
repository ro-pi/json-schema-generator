<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator\Keyword;

use Ropi\JsonSchemaGenerator\GenerationContext\GenerationContext;

class AdditionalPropertiesKeyword implements KeywordInterface
{
    public function mutateSchema(GenerationContext $context): void
    {
        $instance = $context->getCurrentInstance();
        if (!is_object($instance)) {
            return;
        }

        $context->getCurrentSchema()->additionalProperties = false;
    }
}