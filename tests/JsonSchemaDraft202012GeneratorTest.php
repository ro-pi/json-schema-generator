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
                'lastname' => 'Bar',
                'id' => 1,
                'favNums' => [1,2,3]
            ],
            (object) [
                'firstname' => 'Max',
                'lastname' => 'Mustermann',
                'id' => 2,
                'favNums' => [7,9]
            ],
            (object) [
                'firstname' => 'Hello',
                'lastname' => 'Bar',
                'favNums' => [1,2,3,4]
            ]
        ];

        foreach ($instances as $instance) {
            $jsonSchemaGenerator->recordInstance($instance);
        }

        var_dump(json_encode($jsonSchemaGenerator->generateSchema(), JSON_PRETTY_PRINT));exit();
    }
}
