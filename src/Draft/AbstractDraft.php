<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator\Draft;

use Ropi\JsonSchemaGenerator\Context\RecordContext;
use Ropi\JsonSchemaGenerator\Keyword\Exception\InterruptSchemaMutationException;
use Ropi\JsonSchemaGenerator\Keyword\GeneratingKeywordInterface;
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

    public function recordInstance(RecordContext $context): void
    {
        foreach ($this->getKeywords() as $keyword) {
            try {
                $keyword->recordInstance($context);
            } catch (InterruptSchemaMutationException) {
                break;
            }
        }
    }

    public function generateSchema(): void
    {
        foreach ($this->getKeywords() as $keyword) {
            if ($keyword instanceof GeneratingKeywordInterface) {
                $keyword->generateSchema();
            }
        }
    }
}