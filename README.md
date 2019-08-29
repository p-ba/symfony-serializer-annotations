# symfony/serializer annotations
[![Build Status](https://travis-ci.org/p-ba/symfony-serializer-annotations.svg?branch=master)](https://travis-ci.org/p-ba/symfony-serializer-annotations)

Provides additional functionality to standard symfony/seriazlier component

```php
//Input data
$json = '{"testProperty": "testValue"}';

class SomeClass {
  /**
   * @PBA\Serializer\Annotation\DeserializedName(name="testProperty")
   */
  public $property;
}

$someClassInstance = $serializer->deserealize($json, SomeClass::class, 'json');

echo $someClassInstance->property; //echoes 'testValue'
