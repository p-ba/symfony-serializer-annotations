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

    /**
     * Build property annotations cache
     *
     * @param mixed $object
     *
     * @return void
     * @throws \ReflectionException
     */
    protected function buildReaderCache($object)
    {
        if (array_key_exists(get_class($object), $this->readerCache)) {
            return;
        }
        //Try to find MappedProperty annotation
        $reflectionClass = new \ReflectionClass($object);
        foreach ($reflectionClass->getProperties() as $property) {
            /** @var MappedProperty $annotation */
            $annotation = $this->reader->getPropertyAnnotation($property, MappedProperty::class);
            if ($annotation instanceof MappedProperty) {
                $this->readerCache[$reflectionClass->getName()][$annotation->name] = $property->getName();
            }
        }
    }

    protected function getAttributeName($object, $attribute)
    {
        $class = get_class($object);
        $this->buildReaderCache($object);
        if (array_key_exists($attribute, $this->readerCache[$class])) {
            return $this->readerCache[$class][$attribute];
        }
        return $attribute;
    }

    /**
     * {@inheritdoc}
     */
    protected function setAttributeValue($object, $attribute, $value, $format = null, array $context = array())
    {
        $attribute = $this->getAttributeName($object, $attribute);
        parent::setAttributeValue($object, $attribute, $value, $format, $context);
    }
}
