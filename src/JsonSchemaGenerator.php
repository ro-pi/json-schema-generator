<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator;

use Ropi\JsonSchemaGenerator\GenerationConfig\GenerationConfig;
use Ropi\JsonSchemaGenerator\Context\RecordContext;

class JsonSchemaGenerator implements JsonSchemaGeneratorInterface
{
    protected /*readonly*/ RecordContext $Context;

    public function __construct(GenerationConfig $config)
    {
        $this->RecordContext = new RecordContext($config);
    }

    public function recordInstance(mixed $instance): void
    {
        $this->RecordContext->pushInstance($instance);
        $this->RecordContext->config->draft->recordInstance($this->RecordContext);
        $this->RecordContext->popInstance();
    }

    public function generateSchema(): object
    {
        $this->RecordContext->config->draft->generateSchema();
        return $this->RecordContext->getCurrentSchema();
    }
}
