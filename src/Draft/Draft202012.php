<?php
declare(strict_types=1);

namespace Ropi\JsonSchemaGenerator\Draft;

use Ropi\JsonSchemaGenerator\Keyword\AdditionalPropertiesKeyword;
use Ropi\JsonSchemaGenerator\Keyword\ExamplesKeyword;
use Ropi\JsonSchemaGenerator\Keyword\MaxLengthKeyword;
use Ropi\JsonSchemaGenerator\Keyword\MinLengthKeyword;
use Ropi\JsonSchemaGenerator\Keyword\PropertiesKeyword;
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
        $this->registerKeyword(new PropertiesKeyword());
        $this->registerKeyword(new AdditionalPropertiesKeyword());
        $this->registerKeyword(new MinLengthKeyword());
        $this->registerKeyword(new MaxLengthKeyword());
        $this->registerKeyword(new ExamplesKeyword());
    }
}