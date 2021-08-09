# A JSON Schema generator for PHP

This library is a PHP based implementation for generating [JSON Schemas](https://json-schema.org/), which can be easily extended with your own keywords and drafts.
The library is most suitable for the case when you have many instances/datasets and a schema must be generated based on these instances/datasets.

The following drafts are natively supported:
* [Draft 2020-12](https://json-schema.org/draft/2020-12/json-schema-validation.html)

### Requirements
* PHP ^8.0
## Installation
The library can be installed from a command line interface by using [composer](https://getcomposer.org/).

```
composer require ropi/json-schema-generator
```

## Basic usage
```php
<?php
$instances = [
    (object) [
        'firstname' => 'Foo',
        'age' => 18
    ],
    (object) [
        'firstname' => 'Bar',
        'age' => 29,
        'country' => 'DE'
    ]
];

$config = new \Ropi\JsonSchemaGenerator\Config\GenerationConfig(
    draft: new \Ropi\JsonSchemaGenerator\Draft\Draft202012()
);

$generator = new \Ropi\JsonSchemaGenerator\JsonSchemaGenerator($config);

foreach ($instances as $instance) {
    $generator->recordInstance($instance);
}

echo json_encode($generator->generateSchema(), JSON_PRETTY_PRINT);
```
Output of above example: 
```json
{
    "$schema": "https:\/\/json-schema.org\/draft\/2020-12\/schema",
    "type": "object",
    "required": [
        "firstname",
        "age"
    ],
    "properties": {
        "firstname": {
            "type": "string",
            "enum": [
                "Foo",
                "Bar"
            ]
        },
        "age": {
            "type": "integer",
            "enum": [
                18,
                29
            ]
        },
        "country": {
            "type": "string",
            "enum": [
                "DE"
            ]
        }
    },
    "additionalProperties": false
}
```
The generated schema is as restrictive as possible. The more instances are recorded, the better the generated schema is.
Here is an example with more instances:
```php
<?php
$instances = [
    (object) [
        'firstname' => 'Foo',
        'age' => 18
    ],
    (object) [
        'firstname' => 'Bar',
        'age' => 29,
        'country' => 'DE'
    ],
    (object) [
        'firstname' => 'Abc',
        'age' => 31,
        'country' => 'DE'
    ],
    (object) [
        'firstname' => 'Def',
        'age' => 44,
        'country' => 'AT'
    ],
    (object) [
        'firstname' => 'Ghi',
        'age' => 23,
        'country' => 'FR'
    ],
    (object) [
        'firstname' => 'Jkl',
        'age' => 33
    ],
    (object) [
        'firstname' => 'Mnop',
        'age' => 34
    ],
    (object) [
        'firstname' => 'Qrstuvw',
        'age' => 50
    ],
    (object) [
        'firstname' => 'xyz',
        'age' => 56
    ]
];

$config = new \Ropi\JsonSchemaGenerator\Config\GenerationConfig(
    draft: new \Ropi\JsonSchemaGenerator\Draft\Draft202012(),
    maxEnumSize: 8
);

$generator = new \Ropi\JsonSchemaGenerator\JsonSchemaGenerator($config);

foreach ($instances as $instance) {
    $generator->recordInstance($instance);
}

echo json_encode($generator->generateSchema(), JSON_PRETTY_PRINT);
```
Output of above example:
```json

{
  "$schema": "https:\/\/json-schema.org\/draft\/2020-12\/schema",
  "type": "object",
  "required": [
    "firstname",
    "age"
  ],
  "properties": {
    "firstname": {
      "type": "string",
      "minLength": 3,
      "maxLength": 7,
      "examples": [
        "Foo",
        "Bar",
        "Abc",
        "Def",
        "Ghi",
        "Jkl",
        "Mnop",
        "Qrstuvw",
        "xyz"
      ]
    },
    "age": {
      "type": "integer",
      "minimum": 18,
      "maximum": 56,
      "examples": [
        18,
        29,
        31,
        44,
        23,
        33,
        34,
        50,
        56
      ]
    },
    "country": {
      "type": "string",
      "enum": [
        "DE",
        "AT",
        "FR"
      ]
    }
  },
  "additionalProperties": false
}
```