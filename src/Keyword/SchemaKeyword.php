<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator\Keyword;

use Ropi\JsonSchemaGenerator\GenerationContext\GenerationContext;

class SchemaKeyword implements KeywordInterface
{
    public function recordInstance(GenerationContext $context): void
    {
        if ($context->getCurrentSchemaLevel() === 0) {
            $context->getCurrentSchema()->{'$schema'} = $context->config->draft->getUri();
        }
    }
}