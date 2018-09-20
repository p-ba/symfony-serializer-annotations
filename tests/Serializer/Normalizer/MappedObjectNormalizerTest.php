<?php
declare(strict_types=1);

namespace PBA\Serializer\Normalizer;

use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Serializer;

class MappedObjectNormalizerTest extends TestCase
{
    public function testDenormalize()
    {
        $testData = ['test1' => 'testValue'];
        $reader = new AnnotationReader();
        $metadataFactory = new ClassMetadataFactory(new AnnotationLoader($reader));
        $propertyAccessor = new PropertyAccessor();
        $propertyTypeExtractor = new ReflectionExtractor();
        $normalizer = new MappedObjectNormalizer($metadataFactory, null, $propertyAccessor, $propertyTypeExtractor, null, $reader);
        $serializer = new Serializer();
        /** @var TestClass $result */
        $result = $normalizer->denormalize($testData, TestClass::class, 'json');
        $this->assertEquals('testValue', $result->getTestProperty());
    }
}
