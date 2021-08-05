<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator\Draft;

use Ropi\JsonSchemaGenerator\Keyword\KeywordInterface;
use Ropi\JsonSchemaGenerator\GenerationContext\GenerationContext;

interface DraftInterface
{
    function getUri(): string;
    function registerKeyword(KeywordInterface $keyword): void;
    function mutateSchema(GenerationContext $context): void;
}