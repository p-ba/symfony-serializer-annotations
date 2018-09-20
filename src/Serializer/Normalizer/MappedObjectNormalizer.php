<?php
declare(strict_types=1);

namespace PBA\Serializer\Normalizer;

use Doctrine\Common\Annotations\Reader;
use PBA\Serializer\Annotation\MappedProperty;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyInfo\PropertyTypeExtractorInterface;
use Symfony\Component\Serializer\Mapping\ClassDiscriminatorResolverInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class MappedObjectNormalizer extends ObjectNormalizer
{
    /**
     * @var Reader
     */
    protected $reader;

    /**
     * @var array
     */
    protected $readerCache = [];

    public function __construct(
        ClassMetadataFactoryInterface $classMetadataFactory = null,
        NameConverterInterface $nameConverter = null,
        PropertyAccessorInterface $propertyAccessor = null,
        PropertyTypeExtractorInterface $propertyTypeExtractor = null,
        ClassDiscriminatorResolverInterface $classDiscriminatorResolver = null,
        Reader $reader = null
    ) {
        parent::__construct($classMetadataFactory, $nameConverter, $propertyAccessor, $propertyTypeExtractor, $classDiscriminatorResolver);
        $this->reader = $reader;
    }

    protected function buildReaderCache($object)
    {
        if (array_key_exists(get_class($object), $this->readerCache)) {
            return;
        }
        //Try to find MappedProperty annotation
        $reflectionClass = new \ReflectionClass($object);
        foreach ($reflectionClass->getProperties() as $property) {
            $annotation = $this->reader->getPropertyAnnotation($property, MappedProperty::class);
            if ($annotation) {
                $this->readerCache[$reflectionClass->getName()][$annotation->name] = $property->getName();
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function setAttributeValue($object, $attribute, $value, $format = null, array $context = array())
    {
        $this->buildReaderCache($object);
        if ($property = $this->readerCache[get_class($object)][$attribute]) {
            parent::setAttributeValue($object, $property, $value, $format, $context);
            return;
        }
        parent::setAttributeValue($object, $attribute, $value, $format, $context);
    }
}
