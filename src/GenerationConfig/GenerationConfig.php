<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator\GenerationConfig;

use Ropi\CardinalityEstimation\Factory\CardinalityEstimatorFactoryInterface;
use Ropi\CardinalityEstimation\Factory\HyperLogLogCardinalityEstimatorFactory;
use Ropi\JsonSchemaGenerator\Draft\DraftInterface;

class GenerationConfig
{
    public /*readonly*/ CardinalityEstimatorFactoryInterface $cardinalityEstimatorFactory;

    public function __construct(
        public /*readonly*/ DraftInterface $draft,
        ?CardinalityEstimatorFactoryInterface $cardinalityEstimatorFactory = null,
        public /*readonly*/ int $maxExampleValues = 48,
        public /*readonly*/ int $maxEnumSize = 48,
    ) {
        $this->cardinalityEstimatorFactory = $cardinalityEstimatorFactory ?? new HyperLogLogCardinalityEstimatorFactory();
    }
}
