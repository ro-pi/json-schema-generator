<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator;

interface JsonSchemaGeneratorInterface
{
    function recordInstance(object $instance): void;
    function generateSchema(): object;
}
