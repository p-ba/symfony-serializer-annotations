# symfony/serializer annotations

Provides additional functionality to standard symfony/seriazlier component

```php
//Input data
$json = '{"testProperty": "testValue"}';

class SomeClass {
  /**
   * @PBA\Serializer\Annotation\MappedProperty(name="testProperty")
   */
  public $property;
}

$someClassInstance = $serializer->deserealize($json, SomeClass::class, 'json');

echo $someClassInstance->property; //echoes 'testValue'
