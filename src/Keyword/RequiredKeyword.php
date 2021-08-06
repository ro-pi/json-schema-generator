<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator\Keyword;

use Ropi\JsonSchemaGenerator\GenerationContext\GenerationContext;

class RequiredKeyword implements KeywordInterface
{
    public function recordInstance(GenerationContext $context): void
    {
        $instance = $context->getCurrentInstance();
        if (!is_object($instance)) {
            return;
        }

        $schema = $context->getCurrentSchema();
        if (!isset($schema->required)) {
            $schema->required = array_keys(get_object_vars($instance));
            return;
        }

        foreach ($schema->required as $propertyKey => $property) {
            if (!isset($instance->{$property})) {
                unset($schema->required[$propertyKey]);
            }
        }
    }
}