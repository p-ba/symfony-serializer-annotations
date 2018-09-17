<?php
declare(strict_types=1);

namespace PBA\Serializer\Normalizer;

use PBA\Serializer\Annotation\MappedProperty;

class TestClass
{
    /**
     * @MappedProperty(name="test1")
     */
    protected $testProperty;

    public function setTestProperty($testProperty)
    {
        $this->testProperty = $testProperty;
    }

    public function getTestProperty()
    {
        return $this->testProperty;
    }
}