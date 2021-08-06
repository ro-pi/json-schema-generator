<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator\Keyword;

trait SchemaDataMapTrait
{
    private \WeakMap $schemaDataMap;

    public function __construct()
    {
        $this->schemaDataMap = new \WeakMap();
    }

    protected function setSchemaData(object $schema, mixed $data): void
    {
        $this->schemaDataMap[$schema] = $data;
    }

    protected function hasSchemaData(object $schema): bool
    {
        return isset($this->schemaDataMap[$schema]);
    }

    protected function getSchemaData(object $schema): mixed
    {
        return $this->schemaDataMap[$schema];
    }

    protected function getSchemaDataMap(): \WeakMap
    {
        return $this->schemaDataMap;
    }
}