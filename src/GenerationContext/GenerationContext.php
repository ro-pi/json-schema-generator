<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator\GenerationContext;

use Ropi\JsonSchemaGenerator\Exception\JsonSchemaGeneratorException;
use Ropi\JsonSchemaGenerator\GenerationConfig\GenerationConfig;
use Ropi\JsonSchemaGenerator\GenerationContext\Exception\UnsupportedInstanceTypeException;
use Ropi\JsonSchemaGenerator\Keyword\Exception\KeywordSchemaMutationException;

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
        $this->instanceStack[++$this->instanceStackPointer] = [
            'instance' => $instance,
            'instanceHash' => is_scalar($instance) ? crc32((string) $instance) : ''
        ];
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
        return $this->instanceStack[$this->instanceStackPointer]['instance'];
    }

    public function getCurrentInstanceHash(): mixed
    {
        return $this->instanceStack[$this->instanceStackPointer]['instanceHash'];
    }

    /**
     * @throws UnsupportedInstanceTypeException
     */
    public function getCurrentInstanceJsonSchemaType(): string
    {
        $instance = $this->getCurrentInstance();

        return match (true) {
            is_object($instance) => 'object',
            is_array($instance) => 'array',
            is_string($instance) => 'string',
            is_int($instance) => 'integer',
            is_float($instance) => 'number',
            is_bool($instance) => 'boolean',
            is_null($instance) => 'null',
            default => throw new UnsupportedInstanceTypeException(
                'Can not map instance with type "'
                . gettype($instance)
                . '" to a suitable JSON Schema type',
                1628197539
            )
        };
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