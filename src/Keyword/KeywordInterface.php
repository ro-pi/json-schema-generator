<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator\Keyword;

use Ropi\JsonSchemaGenerator\GenerationContext\GenerationContext;
use Ropi\JsonSchemaGenerator\Keyword\Exception\InterruptSchemaMutationException;

interface KeywordInterface
{
    /**
     * @throws InterruptSchemaMutationException
     */
    function mutateSchema(GenerationContext $context): void;
}