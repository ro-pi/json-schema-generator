<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator;

use Ropi\CardinalityEstimation\Factory\CardinalityEstimatorFactoryInterface;
use Ropi\JsonSchemaGenerator\Schema\ArraySchema;
use Ropi\JsonSchemaGenerator\Schema\BasicTypeSchema;
use Ropi\JsonSchemaGenerator\Schema\BooleanSchema;
use Ropi\JsonSchemaGenerator\Schema\IntegerSchema;
use Ropi\JsonSchemaGenerator\Schema\NullSchema;
use Ropi\JsonSchemaGenerator\Schema\NumberSchema;
use Ropi\JsonSchemaGenerator\Schema\ObjectSchema;
use Ropi\JsonSchemaGenerator\Schema\Schema;
use Ropi\JsonSchemaGenerator\Schema\StringSchema;
use Ropi\JsonSchemaGenerator\Schema\RootSchema;

class _JsonSchemaGenerator implements JsonSchemaGeneratorInterface
{
    protected string $schemaVersion = 'https://json-schema.org/draft/2020-12/schema';
    private CardinalityEstimatorFactoryInterface $cardinalityEstimatorFactory;
    protected \SplObjectStorage $cardinalityEstimatorStorage;
    protected ?RootSchema $schema;
    private int $maxExampleValues = 48;
    private int $maxEnumSize = 48;

    public function __construct(CardinalityEstimatorFactoryInterface $cardinalityEstimatorFactory)
    {
        $this->cardinalityEstimatorFactory = $cardinalityEstimatorFactory;
        $this->reset();
    }

    public function reset(): void
    {
        $this->cardinalityEstimatorStorage = new \SplObjectStorage();
        $this->schema = null;
    }

    public function record(object $dataset): void
    {
        if (!$this->schema) {
            $this->schema = $this->createRootSchema($dataset);
        }

        $this->recordValue($this->schema, $dataset);
    }

    protected function recordValue(Schema $schema, mixed $value): void
    {
        $type = $this->mapJsonSchemaType($value);
        if (!isset($schema->anyOf[$type])) {
            $schema->anyOf[$type] = $this->createSchemaForType($type, $value);
        }

        if ($schema->anyOf[$type] instanceof ObjectSchema) {
            $this->recordObject($schema->anyOf[$type], $value);
        } elseif ($schema->anyOf[$type] instanceof ArraySchema) {
            $this->recordArray($schema->anyOf[$type], $value);
        } else {
            $this->recordBasicValue($schema->anyOf[$type], $value);
        }
    }

    protected function recordObject(ObjectSchema $schema, object $object): void
    {
        foreach ($object as $propertyName => $propertyValue) {
            if (!isset($schema->properties[$propertyName])) {
                $schema->properties[$propertyName] = new Schema();
            }

            $this->recordValue($schema->properties[$propertyName], $propertyValue);
        }

        $schema->recordValue($object);
    }

    protected function recordArray(ArraySchema $schema, array $array): void
    {
        $schema->recordValue($array);

        foreach ($array as $value) {
            $type = $this->mapJsonSchemaType($value);
            if (!isset($schema->items[$type])) {
                $schema->items[$type] = $this->createSchemaForType($type, $value);
            }

            $this->recordValue($schema->items[$type], $value);
        }
    }

    protected function recordBasicValue(BasicTypeSchema $schema, mixed $value): void
    {
        $schema->recordValue($value);

        $numExamples = count($schema->examples);
        if ($numExamples < $this->getMaxExampleValues()) {
            $valueHash = crc32((string) $value);
            $schema->examples[$valueHash] = $value;

            if ($numExamples >= $this->getMaxExampleValues()) {
                $cardinalityEstimator = $this->getCardinalityEstimatorFactory()->create();

                foreach ($schema->examples as $exampleValue) {
                    $cardinalityEstimator->addValue((string) $exampleValue);
                }

                $this->cardinalityEstimatorStorage->offsetSet($schema, $cardinalityEstimator);
            }
        }

        if ($this->cardinalityEstimatorStorage->offsetExists($schema)) {
            $this->cardinalityEstimatorStorage->offsetGet($schema)->addValue((string) $value);
        }
    }

    protected function createRootSchema(object $dataset): RootSchema
    {
        return new RootSchema($dataset, $this->getSchemaVersion());
    }

    protected function createSchemaForType(string $type, mixed $firstValue): Schema
    {
        return match ($type) {
            'object' => new ObjectSchema($firstValue),
            'array' => new ArraySchema($firstValue),
            'string' => new StringSchema($firstValue),
            'integer' => new IntegerSchema($firstValue),
            'number' => new NumberSchema($firstValue),
            'boolean' => new BooleanSchema(),
            'null' => new NullSchema(),
            default => throw new \RuntimeException(
                'Values of type "'
                . gettype($firstValue)
                . '" are not supported for JSON schema generation',
                1619217701
            )
        };
    }

    public function generate(): object
    {
        if (!$this->schema) {
            return $this->createRootSchema(new \stdClass());
        }

        return $this->generateSchema($this->schema);
    }

    protected function generateSchema(object $schema): object
    {
        $schema = $this->schemaToPlainObject($schema);

        $this->generateAnyOf($schema);
        $this->generateItems($schema);
        $this->generateRequired($schema);
        $this->generateProperties($schema);
        $this->generateExamples($schema);
        $this->generateEnum($schema);
        $this->generateMeta($schema);

        return $schema;
    }

    protected function schemaToPlainObject(object $schema): object
    {
        if ($schema instanceof Schema) {
            $objectVars = get_object_vars($schema);
            ksort($objectVars);
            $schema = (object) $objectVars;
        }

        return $schema;
    }

    protected function generateRequired(object $schema): void
    {
        if (isset($schema->required) && !empty($schema->required)) {
            // Convert to sequential array
            $schema->required = array_values($schema->required);
        } else {
            unset($schema->required);
        }
    }

    protected function generateProperties(object $schema): void
    {
        if (isset($schema->properties) && !empty($schema->properties)) {
            foreach ($schema->properties as &$propertySchema) {
                $propertySchema = $this->generateSchema($propertySchema);
            }
        } else {
            unset($schema->properties);
        }
    }

    protected function generateExamples(object $schema): void
    {
        if (isset($schema->examples) && !empty($schema->examples)) {
            // Convert to sequential array
            $schema->examples = array_values($schema->examples);

            if (count($schema->examples) <= $this->getMaxEnumSize()) {
                $schema->enum = $schema->examples;
            }
        } else {
            unset($schema->examples);
        }
    }

    protected function generateEnum(object $schema): void
    {
        if (isset($schema->examples) && count($schema->examples) <= $this->getMaxEnumSize()) {
            $schema->enum = $schema->examples;
            unset($schema->minimum);
            unset($schema->maximum);
            unset($schema->minLength);
            unset($schema->maxLength);
        }
    }

    protected function generateAnyOf(object $schema): void
    {
        if (isset($schema->anyOf) && !empty($schema->anyOf)) {
            foreach ($schema->anyOf as &$anyOfSchema) {
                $anyOfSchema = $this->generateSchema($anyOfSchema);
            }

            $schema->anyOf = array_values($schema->anyOf);
            if (count($schema->anyOf) === 1) {
                foreach ($schema->anyOf[0] as $key => $value) {
                    $schema->$key = $value;
                }

                unset($schema->anyOf);
            }
        } else {
            unset($schema->anyOf);
        }
    }

    protected function generateItems(object $schema): void
    {
        if (isset($schema->items) && !empty($schema->items)) {
            foreach ($schema->items as &$itemSchema) {
                $itemSchema = $this->generateSchema($itemSchema);
            }

            $schema->items = array_values($schema->items);
        } else {
            unset($schema->items);
        }
    }

    protected function generateMeta(object $schema): void
    {
        $schema->_meta = new \stdClass();
        $this->generateEstimatedCardinality($schema);
    }

    protected function generateEstimatedCardinality(object $schema): void
    {
        $schema->_meta->estimatedCardinality = 0;

        if ($this->cardinalityEstimatorStorage->offsetExists($schema)) {
            $cardinalityEstimator = $this->cardinalityEstimatorStorage->offsetGet($schema);
            $schema->_meta->estimatedCardinality = $cardinalityEstimator->estimate();
        } else {
            $schema->_meta->estimatedCardinality = isset($schema->examples) ? count($schema->examples) : 0;
        }
    }

    protected function mapJsonSchemaType(mixed $value): string
    {
        return match (true) {
            is_object($value) => 'object',
            is_array($value) => 'array',
            is_string($value) => 'string',
            is_int($value) => 'integer',
            is_float($value) => 'float',
            is_bool($value) => 'boolean',
            is_null($value) => 'null',
            default => throw new \InvalidArgumentException(
                'Can not map from value with type "'
                . gettype($value)
                . '" to JSON type, because the type is not supported',
                1619217701
            )
        };
    }

    public function getSchemaVersion(): string
    {
        return $this->schemaVersion;
    }

    public function getCardinalityEstimatorFactory(): CardinalityEstimatorFactoryInterface
    {
        return $this->cardinalityEstimatorFactory;
    }

    public function getMaxExampleValues(): int
    {
        return $this->maxExampleValues;
    }

    public function setMaxExampleValues(int $maxExampleValues): void
    {
        $this->maxExampleValues = $maxExampleValues;
    }

    public function getMaxEnumSize(): int
    {
        return $this->maxEnumSize;
    }

    public function setMaxEnumSize(int $maxEnumSize): void
    {
        $this->maxEnumSize = $maxEnumSize;
    }
}
