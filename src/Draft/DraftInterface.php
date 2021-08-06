<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator\Draft;

use Ropi\JsonSchemaGenerator\Keyword\KeywordInterface;
use Ropi\JsonSchemaGenerator\Context\RecordContext;

interface DraftInterface
{
    function getUri(): string;
    function registerKeyword(KeywordInterface $keyword): void;
    function recordInstance(RecordContext $context): void;
    function generateSchema(): void;
}