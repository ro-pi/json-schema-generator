<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator;

use Ropi\JsonSchemaGenerator\Config\GenerationConfig;
use Ropi\JsonSchemaGenerator\Context\RecordContext;

class JsonSchemaGenerator implements JsonSchemaGeneratorInterface
{
    protected readonly RecordContext $recordContext;

    public function __construct(GenerationConfig $config)
    {
        $this->recordContext = new RecordContext($config);
    }

    public function recordInstance(mixed $instance): void
    {
        $this->recordContext->pushInstance($instance);
        $this->recordContext->config->draft->recordInstance($this->recordContext);
        $this->recordContext->popInstance();
    }

    public function generateSchema(): object
    {
        $this->recordContext->config->draft->generateSchema();
        return $this->recordContext->getCurrentSchema();
    }
}
