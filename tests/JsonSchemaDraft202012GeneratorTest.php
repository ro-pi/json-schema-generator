<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator\Tests;

use PHPUnit\Framework\TestCase;
use Ropi\CardinalityEstimation\Factory\ExactCardinalityEstimatorFactory;
use Ropi\JsonSchemaGenerator\Draft\Draft202012;
use Ropi\JsonSchemaGenerator\GenerationConfig\GenerationConfig;
use Ropi\JsonSchemaGenerator\JsonSchemaGenerator;

class JsonSchemaDraft202012GeneratorTest extends TestCase
{
    public function getDefaultConfig(): GenerationConfig
    {
        return new GenerationConfig(
            new Draft202012(),
            new ExactCardinalityEstimatorFactory()
        );
    }

    public function testBasicUsage(): void
    {
        $jsonSchemaGenerator = new JsonSchemaGenerator($this->getDefaultConfig());

        $instances = [
            (object) [
                'firstname' => 'Foo',
                'lastname' => 'Bar'
            ],
            (object) [
                'firstname' => 'Max',
                'lastname' => 'Mustermann'
            ],
            (object) [
                'firstname' => 'Hello',
                'lastname' => 'Bar'
            ]
        ];

        foreach ($instances as $instance) {
            $jsonSchemaGenerator->recordInstance($instance);
        }

        var_dump(json_encode($jsonSchemaGenerator->generateSchema(), JSON_PRETTY_PRINT));exit();
    }
}
