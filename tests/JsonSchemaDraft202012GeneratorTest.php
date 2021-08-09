<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator\Tests;

use PHPUnit\Framework\TestCase;
use Ropi\CardinalityEstimation\Factory\ExactCardinalityEstimatorFactory;
use Ropi\JsonSchemaGenerator\Draft\Draft202012;
use Ropi\JsonSchemaGenerator\Config\GenerationConfig;
use Ropi\JsonSchemaGenerator\JsonSchemaGenerator;

class JsonSchemaDraft202012GeneratorTest extends TestCase
{
    public function testString(): void
    {
        $jsonSchemaGenerator = new JsonSchemaGenerator(new GenerationConfig(
            new Draft202012(),
            null,
            2,
            2
        ));

        $instances = [
            'a',
            'b',
            'abc',
            'cde'
        ];

        foreach ($instances as $instance) {
            $jsonSchemaGenerator->recordInstance($instance);
        }

        $generatedSchema = $jsonSchemaGenerator->generateSchema();

        $this->assertObjectHasAttribute('type', $generatedSchema, 'type property was generated');
        $this->assertObjectHasAttribute('minLength', $generatedSchema, 'minLength property was generated');
        $this->assertObjectHasAttribute('maxLength', $generatedSchema, 'maxLength property was generated');

        $this->assertEquals('string', $generatedSchema->type, 'Generate string type');
        $this->assertEquals(1, $generatedSchema->minLength, 'Generate minLength from shortest recorded string');
        $this->assertEquals(3, $generatedSchema->maxLength, 'Generate maxLength from longest recorded string');
    }

    public function testNumber(): void
    {
        $jsonSchemaGenerator = new JsonSchemaGenerator(new GenerationConfig(
            new Draft202012(),
            null,
            2,
            2
        ));

        $instances = [
            1,
            2,
            3.3,
            4
        ];

        foreach ($instances as $instance) {
            $jsonSchemaGenerator->recordInstance($instance);
        }

        $generatedSchema = $jsonSchemaGenerator->generateSchema();

        $this->assertObjectHasAttribute('type', $generatedSchema, 'type property was generated');
        $this->assertObjectHasAttribute('minimum', $generatedSchema, 'minimum property was generated');
        $this->assertObjectHasAttribute('maximum', $generatedSchema, 'maximum property was generated');

        $this->assertEquals('number', $generatedSchema->type, 'Generate number type');
        $this->assertEquals(1, $generatedSchema->minimum, 'Generate minimum from lowest recorded number');
        $this->assertEquals(4, $generatedSchema->maximum, 'Generate maximum from highest recorded number');
    }

    public function testInteger(): void
    {
        $jsonSchemaGenerator = new JsonSchemaGenerator(new GenerationConfig(
            new Draft202012(),
            null,
            2,
            2
        ));

        $instances = [
            1,
            2,
            3,
            4
        ];

        foreach ($instances as $instance) {
            $jsonSchemaGenerator->recordInstance($instance);
        }

        $generatedSchema = $jsonSchemaGenerator->generateSchema();

        $this->assertObjectHasAttribute('type', $generatedSchema, 'type property was generated');
        $this->assertObjectHasAttribute('minimum', $generatedSchema, 'minimum property was generated');
        $this->assertObjectHasAttribute('maximum', $generatedSchema, 'maximum property was generated');

        $this->assertEquals('integer', $generatedSchema->type, 'Generate integer type');
        $this->assertEquals(1, $generatedSchema->minimum, 'Generate minimum from lowest recorded number');
        $this->assertEquals(4, $generatedSchema->maximum, 'Generate maximum from highest recorded number');
    }

    public function testBoolean(): void
    {
        $jsonSchemaGenerator = new JsonSchemaGenerator(new GenerationConfig(
            new Draft202012(),
            null,
            2,
            2
        ));

        $instances = [
            false,
            false,
            true
        ];

        foreach ($instances as $instance) {
            $jsonSchemaGenerator->recordInstance($instance);
        }

        $generatedSchema = $jsonSchemaGenerator->generateSchema();

        $this->assertObjectHasAttribute('type', $generatedSchema, 'type property was generated');

        $this->assertEquals('boolean', $generatedSchema->type, 'Generate boolean type');
    }

    public function testNull(): void
    {
        $jsonSchemaGenerator = new JsonSchemaGenerator(new GenerationConfig(
            new Draft202012(),
            null,
            2,
            2
        ));

        $instances = [
            null,
            null,
            null
        ];

        foreach ($instances as $instance) {
            $jsonSchemaGenerator->recordInstance($instance);
        }

        $generatedSchema = $jsonSchemaGenerator->generateSchema();

        $this->assertObjectHasAttribute('type', $generatedSchema, 'type property was generated');

        $this->assertEquals('null', $generatedSchema->type, 'Generate null type');
    }

    public function testArray(): void
    {
        $jsonSchemaGenerator = new JsonSchemaGenerator(new GenerationConfig(
            new Draft202012(),
            null,
            2,
            2
        ));

        $instances = [
            [1, 2, 3],
            [1, 2],
            [1]
        ];

        foreach ($instances as $instance) {
            $jsonSchemaGenerator->recordInstance($instance);
        }

        $generatedSchema = $jsonSchemaGenerator->generateSchema();

        $this->assertObjectHasAttribute('type', $generatedSchema, 'type property was generated');
        $this->assertObjectHasAttribute('contains', $generatedSchema, 'contains property was generated');
        $this->assertObjectHasAttribute('minItems', $generatedSchema, 'minItems property was generated');
        $this->assertObjectHasAttribute('maxItems', $generatedSchema, 'maxItems property was generated');

        $this->assertEquals('array', $generatedSchema->type, 'Generate array type');
        $this->assertEquals(1, $generatedSchema->minItems, 'Generate minItems from smallest array');
        $this->assertEquals(3, $generatedSchema->maxItems, 'Generate maxItems from largest array');

        $this->assertIsObject($generatedSchema->contains, 'Generated contains scheme from items');
        $this->assertEquals('integer', $generatedSchema->contains->type);
    }

    public function testObject(): void
    {
        $jsonSchemaGenerator = new JsonSchemaGenerator(new GenerationConfig(
            new Draft202012(),
            null,
            2,
            2
        ));

        $instances = [
            (object) [
                'firstName' => 'Foo',
                'lastName' => 'Bar'
            ],
            (object) [
                'lastName' => 'Mustermann'
            ]
        ];

        foreach ($instances as $instance) {
            $jsonSchemaGenerator->recordInstance($instance);
        }

        $generatedSchema = $jsonSchemaGenerator->generateSchema();

        $this->assertObjectHasAttribute('type', $generatedSchema, 'type property was generated');
        $this->assertObjectHasAttribute('required', $generatedSchema, 'required property was generated');
        $this->assertObjectHasAttribute('properties', $generatedSchema, 'properties property was generated');
        $this->assertObjectHasAttribute('additionalProperties', $generatedSchema, 'additionalProperties property was generated');

        $this->assertEquals('object', $generatedSchema->type, 'Generate object type');
        $this->assertEquals(['lastName'], $generatedSchema->required, 'Only lastName is required');

        $this->assertIsObject($generatedSchema->properties->firstName, 'Generated firstName property scheme');
        $this->assertEquals('string', $generatedSchema->properties->firstName->type);

        $this->assertIsObject($generatedSchema->properties->lastName, 'Generated lastName property scheme');
        $this->assertEquals('string', $generatedSchema->properties->lastName->type);
    }

    public function testMixedTypes(): void
    {
        $jsonSchemaGenerator = new JsonSchemaGenerator(new GenerationConfig(
            new Draft202012(),
            null,
            2,
            2
        ));

        $instances = [
            1,
            'string',
            2
        ];

        foreach ($instances as $instance) {
            $jsonSchemaGenerator->recordInstance($instance);
        }

        $generatedSchema = $jsonSchemaGenerator->generateSchema();

        $this->assertObjectNotHasAttribute('anyOf', $generatedSchema, 'anyOf property was NOT generated');
        $this->assertObjectHasAttribute('type', $generatedSchema, 'type property was generated');

        $this->assertEquals(['integer', 'string'], $generatedSchema->type, 'Generated types');
    }

    public function testSchemaDraft(): void
    {
        $draft = new Draft202012();
        $jsonSchemaGenerator = new JsonSchemaGenerator(new GenerationConfig(
            $draft,
            null,
            2,
            2
        ));

        $instances = [
            1,
            'string',
            2
        ];

        foreach ($instances as $instance) {
            $jsonSchemaGenerator->recordInstance($instance);
        }

        $generatedSchema = $jsonSchemaGenerator->generateSchema();

        $this->assertObjectHasAttribute('$schema', $generatedSchema, '$schema property was generated');
        $this->assertEquals($draft->getUri(), $generatedSchema->{'$schema'}, '$schema property matches configured draft URI');
    }

    public function testEnum(): void
    {
        $jsonSchemaGenerator = new JsonSchemaGenerator(new GenerationConfig(
            new Draft202012(),
            null,
            2,
            4
        ));

        $instances = [
            1,
            'string',
            2
        ];

        foreach ($instances as $instance) {
            $jsonSchemaGenerator->recordInstance($instance);
        }

        $generatedSchema = $jsonSchemaGenerator->generateSchema();

        $this->assertObjectHasAttribute('enum', $generatedSchema, 'enum property was generated');
        $this->assertEquals($instances, $generatedSchema->enum, 'enum matches seen instance values');
    }

    public function testExamples(): void
    {
        $jsonSchemaGenerator = new JsonSchemaGenerator(new GenerationConfig(
            new Draft202012(),
            null,
            4,
            2
        ));

        $instances = [
            7,
            'string',
            20
        ];

        foreach ($instances as $instance) {
            $jsonSchemaGenerator->recordInstance($instance);
        }

        $generatedSchema = $jsonSchemaGenerator->generateSchema();

        $this->assertObjectHasAttribute('examples', $generatedSchema, 'examples property was generated');
        $this->assertEquals($instances, $generatedSchema->examples, 'examples matches seen instance values');
    }

    public function testEstimatedCardinality(): void
    {
        $jsonSchemaGenerator = new JsonSchemaGenerator(new GenerationConfig(
            new Draft202012(),
            new ExactCardinalityEstimatorFactory(),
            2,
            2
        ));

        $instances = [];

        for ($i = 0; $i < 1001; $i++) {
            $instances[] = $i;
        }

        foreach ($instances as $instance) {
            $jsonSchemaGenerator->recordInstance($instance);
        }

        $generatedSchema = $jsonSchemaGenerator->generateSchema();

        $this->assertObjectHasAttribute('estimatedCardinality', $generatedSchema, 'estimatedCardinality property was generated');
        $this->assertEquals(1001, $generatedSchema->estimatedCardinality, 'cardinality matches seen unique instances');
    }

    public function testEstimatedCardinalityNoFactoryConfigured(): void
    {
        $jsonSchemaGenerator = new JsonSchemaGenerator(new GenerationConfig(
            new Draft202012(),
            null,
            2,
            2
        ));

        $instances = [];

        for ($i = 0; $i < 1001; $i++) {
            $instances[] = $i;
        }

        foreach ($instances as $instance) {
            $jsonSchemaGenerator->recordInstance($instance);
        }

        $generatedSchema = $jsonSchemaGenerator->generateSchema();

        $this->assertObjectNotHasAttribute('estimatedCardinality', $generatedSchema, 'estimatedCardinality property was NOT generated');
    }
}
