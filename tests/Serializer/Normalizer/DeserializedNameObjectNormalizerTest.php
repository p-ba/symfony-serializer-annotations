<?php
declare(strict_types=1);

namespace PBA\Serializer\Normalizer;

use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;

class DeserializedNameObjectNormalizerTest extends TestCase
{
    public function testDenormalize()
    {
        $testData = ['test1' => 'testValue'];
        $reader = new AnnotationReader();
        $metadataFactory = new ClassMetadataFactory(new AnnotationLoader($reader));
        $propertyAccessor = new PropertyAccessor();
        $propertyTypeExtractor = new ReflectionExtractor();
        $normalizer = new DeserializedNameObjectNormalizer($metadataFactory, null, $propertyAccessor, $propertyTypeExtractor, null, $reader);
        /** @var TestClass $result */
        $result = $normalizer->denormalize($testData, TestClass::class, 'json');
        $this->assertEquals('testValue', $result->getTestProperty());
    }
}
