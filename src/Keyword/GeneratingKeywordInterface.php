<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator\Keyword;

interface GeneratingKeywordInterface extends KeywordInterface
{
    function generateSchema(): void;
}