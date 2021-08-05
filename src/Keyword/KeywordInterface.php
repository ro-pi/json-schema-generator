<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator\Keyword;

use Ropi\JsonSchemaGenerator\GenerationContext\GenerationContext;
use Ropi\JsonSchemaGenerator\Keyword\Exception\KeywordSchemaMutationException;

interface KeywordInterface
{
    /**
     * @throws KeywordSchemaMutationException
     */
    function mutateSchema(GenerationContext $context): void;
}