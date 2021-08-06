<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator\Keyword;

use Ropi\CardinalityEstimation\CardinalityEstimatorInterface;
use Ropi\JsonSchemaGenerator\GenerationContext\GenerationContext;

class EstimatedCardinalityKeyword implements GeneratingKeywordInterface
{
    use SchemaDataMapTrait;

    public function recordInstance(GenerationContext $context): void
    {
        $cardinalityEstimatorFactory = $context->config->cardinalityEstimatorFactory;
        if (!$cardinalityEstimatorFactory) {
            return;
        }

        $instanceHash = $context->getCurrentInstanceHash();
        if (!$instanceHash) {
            return;
        }

        $schema = $context->getCurrentSchema();
        if (!$this->hasSchemaData($schema)) {
            $this->setSchemaData($schema, [$instanceHash => true]);
            return;
        }

        $data = $this->getSchemaData($schema);
        if (!$data) {
            return;
        }

        if ($data instanceof CardinalityEstimatorInterface) {
            $data->addValue((string) $instanceHash);
        } else {
            $data[$instanceHash] = true;

            if (count($data) > 128) {
                $cardinalityEstimator = $cardinalityEstimatorFactory->create();

                foreach ($data as $hash => $_) {
                    $cardinalityEstimator->addValue((string) $hash);
                }

                $data = $cardinalityEstimator;
            }
        }

        $this->setSchemaData($schema, $data);
    }

    public function generateSchema(): void
    {
        foreach ($this->getSchemaDataMap() as $schema => $data) {
            if ($data instanceof CardinalityEstimatorInterface) {
                $schema->estimatedCardinality = $data->estimate();
            } else {
                $schema->estimatedCardinality = count($data);
            }
        }
    }
}