<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator\GenerationContext;

use Ropi\JsonSchemaGenerator\GenerationConfig\GenerationConfig;

class GenerationContext
{
    private array $instanceStack = [];
    private int $instanceStackPointer = 0;

    private array $schemaStack = [];
    private int $schemaStackPointer = 0;

    public function __construct(
        public /*readonly*/ GenerationConfig $config
    ) {
        $this->schemaStack[0] = new \stdClass();
    }

    public function pushInstance(mixed $instance): void
    {
        $this->instanceStack[++$this->instanceStackPointer] = $instance;
    }

    public function popInstance(): void
    {
        if ($this->instanceStackPointer <= 0) {
            throw new \RuntimeException(
                'Can not pop root instance',
                1628193519
            );
        }

        $this->instanceStackPointer--;
    }

    public function getCurrentInstance(): mixed
    {
        return $this->instanceStack[$this->instanceStackPointer];
    }

    public function pushSchema(mixed $schema): void
    {
        $this->schemaStack[++$this->schemaStackPointer] = $schema;
    }

    public function popSchema(): void
    {
        if ($this->schemaStackPointer <= 0) {
            throw new \RuntimeException(
                'Can not pop root schema',
                1628193519
            );
        }

        $this->schemaStackPointer--;
    }

    public function getCurrentSchema(): mixed
    {
        return $this->schemaStack[$this->schemaStackPointer];
    }

    public function getCurrentSchemaLevel(): int
    {
        return $this->schemaStackPointer;
    }
}