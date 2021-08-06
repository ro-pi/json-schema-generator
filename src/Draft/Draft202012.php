<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator\Draft;

use Ropi\JsonSchemaGenerator\Keyword\AdditionalPropertiesKeyword;
use Ropi\JsonSchemaGenerator\Keyword\ContainsKeyword;
use Ropi\JsonSchemaGenerator\Keyword\EnumKeyword;
use Ropi\JsonSchemaGenerator\Keyword\EstimatedCardinalityKeyword;
use Ropi\JsonSchemaGenerator\Keyword\ExamplesKeyword;
use Ropi\JsonSchemaGenerator\Keyword\MaximumKeyword;
use Ropi\JsonSchemaGenerator\Keyword\MaxItemsKeyword;
use Ropi\JsonSchemaGenerator\Keyword\MaxLengthKeyword;
use Ropi\JsonSchemaGenerator\Keyword\MinimumKeyword;
use Ropi\JsonSchemaGenerator\Keyword\MinItemsKeyword;
use Ropi\JsonSchemaGenerator\Keyword\MinLengthKeyword;
use Ropi\JsonSchemaGenerator\Keyword\PropertiesKeyword;
use Ropi\JsonSchemaGenerator\Keyword\RequiredKeyword;
use Ropi\JsonSchemaGenerator\Keyword\SchemaDataMapTrait;
use Ropi\JsonSchemaGenerator\Keyword\SchemaKeyword;
use Ropi\JsonSchemaGenerator\Keyword\TypeKeyword;

class Draft202012 extends AbstractDraft
{
    protected const URI = 'https://json-schema.org/draft/2020-12/schema';

    public function getUri(): string
    {
        return static::URI;
    }

    public function __construct()
    {
        $this->registerKeyword(new SchemaKeyword());
        $this->registerKeyword(new TypeKeyword());
        $this->registerKeyword(new RequiredKeyword());
        $this->registerKeyword(new PropertiesKeyword());
        $this->registerKeyword(new AdditionalPropertiesKeyword());
        $this->registerKeyword(new ContainsKeyword());
        $this->registerKeyword(new MinItemsKeyword());
        $this->registerKeyword(new MaxItemsKeyword());
        $this->registerKeyword(new MinLengthKeyword());
        $this->registerKeyword(new MaxLengthKeyword());
        $this->registerKeyword(new MinimumKeyword());
        $this->registerKeyword(new MaximumKeyword());
        $this->registerKeyword(new EstimatedCardinalityKeyword());
        $this->registerKeyword(new ExamplesKeyword());
        $this->registerKeyword(new EnumKeyword());
    }
}