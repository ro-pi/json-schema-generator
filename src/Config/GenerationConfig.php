<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator\Config;

use Ropi\CardinalityEstimation\Factory\CardinalityEstimatorFactoryInterface;
use Ropi\JsonSchemaGenerator\Draft\DraftInterface;

class GenerationConfig
{
    public /*readonly*/ DraftInterface $draft;

    public function __construct(
        DraftInterface $draft,
        public /*readonly*/ ?CardinalityEstimatorFactoryInterface $cardinalityEstimatorFactory = null,
        public /*readonly*/ int $maxExampleValues = 16,
        public /*readonly*/ int $maxEnumSize = 64
    ) {
        $this->draft = clone $draft;
    }
}
