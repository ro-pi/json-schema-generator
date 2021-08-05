<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator\Schema;

class RootSchema extends ObjectSchema
{
    public function __construct(object $firstValue, string $version)
    {
        $this->{'$schema'} = $version;

        parent::__construct($firstValue);
    }
}
