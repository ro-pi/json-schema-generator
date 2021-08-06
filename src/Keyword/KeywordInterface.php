<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator\Keyword;

use Ropi\JsonSchemaGenerator\Context\RecordContext;
use Ropi\JsonSchemaGenerator\Keyword\Exception\InterruptSchemaMutationException;

interface KeywordInterface
{
    /**
     * @throws InterruptSchemaMutationException
     */
    function recordInstance(RecordContext $context): void;
}