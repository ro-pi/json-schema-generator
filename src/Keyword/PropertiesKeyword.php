<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator\Keyword;

use Ropi\JsonSchemaGenerator\GenerationContext\GenerationContext;

class PropertiesKeyword implements KeywordInterface
{
    public function mutateSchema(GenerationContext $context): void
    {
        $instance = $context->getCurrentInstance();
        if (!is_object($instance)) {
            return;
        }

        $schema = $context->getCurrentSchema();
        if (!isset($schema->properties)) {
            $schema->properties = new \stdClass();
        }

        foreach ($instance as $propertyName => $propertyValue) {
            if (!isset($schema->properties->$propertyName)) {
                $schema->properties->$propertyName = new \stdClass();
            }

            $context->pushSchema($schema->properties->$propertyName);
            $context->pushInstance($propertyValue);

            $context->config->draft->mutateSchema($context);

            $context->popInstance();
            $context->popSchema();
        }
    }
}