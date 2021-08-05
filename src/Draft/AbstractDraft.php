<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator\Draft;

use Ropi\JsonSchemaGenerator\GenerationContext\GenerationContext;
use Ropi\JsonSchemaGenerator\Keyword\KeywordInterface;

abstract class AbstractDraft implements DraftInterface
{
    /**
     * @var KeywordInterface[]
     */
    private array $keywords = [];

    public function registerKeyword(KeywordInterface $keyword): void
    {
        $this->keywords[] = $keyword;
    }

    public function getKeywords(): array
    {
        return $this->keywords;
    }

    public function mutateSchema(GenerationContext $context): void
    {
        foreach ($this->getKeywords() as $keyword) {
            $keyword->mutateSchema($context);
        }
    }
}