<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator;

use Ropi\JsonSchemaGenerator\GenerationConfig\GenerationConfig;
use Ropi\JsonSchemaGenerator\GenerationContext\GenerationContext;

class JsonSchemaGenerator implements JsonSchemaGeneratorInterface
{
    protected /*readonly*/ GenerationContext $generationContext;

    public function __construct(GenerationConfig $config)
    {
        $this->generationContext = new GenerationContext($config);
    }

    public function recordInstance(mixed $instance): void
    {
        $this->generationContext->pushInstance($instance);
        $this->generationContext->config->draft->mutateSchema($this->generationContext);
        $this->generationContext->popInstance();
    }

    public function generateSchema(): object
    {
        return $this->generationContext->getCurrentSchema();
    }
}
