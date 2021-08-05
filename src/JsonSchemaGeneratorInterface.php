<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator;

interface JsonSchemaGeneratorInterface
{
    function record(object $dataset): void;
    function reset(): void;
    function generate(): object;
}
