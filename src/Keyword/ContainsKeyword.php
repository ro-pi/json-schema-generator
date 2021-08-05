<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator\Keyword;

use Ropi\JsonSchemaGenerator\GenerationContext\GenerationContext;

class ContainsKeyword implements KeywordInterface
{
    public function mutateSchema(GenerationContext $context): void
    {
        $instance = $context->getCurrentInstance();
        if (!is_array($instance)) {
            return;
        }

        $schema = $context->getCurrentSchema();
        if (!isset($schema->contains)) {
            $schema->contains = new \stdClass();
        }

        $context->pushSchema($schema->contains);

        foreach ($instance as $item) {
            $context->pushInstance($item);
            $context->config->draft->mutateSchema($context);
            $context->popInstance();
        }

        $context->popSchema();
    }
}