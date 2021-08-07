<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator\Keyword;

use Ropi\JsonSchemaGenerator\Context\RecordContext;

interface KeywordInterface
{
    function recordInstance(RecordContext $context): void;
}