<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator\Schema;

class NullSchema extends BasicTypeSchema
{
    public string $type = 'null';
}
