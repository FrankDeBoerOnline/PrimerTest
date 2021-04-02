<?php

namespace Tests\FrankDeBoerOnline\Configuration;

use FrankDeBoerOnline\Configuration\Configuration;
use FrankDeBoerOnline\Configuration\Error\ResourceErrorInvalidFileType;
use FrankDeBoerOnline\Configuration\Error\ResourceFinderErrorNotFound;
use FrankDeBoerOnline\Configuration\Yamlenv\Exception\ValidationException;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{

    /**
     * @param string $file
     * @throws ResourceErrorInvalidFileType
     * @throws ResourceFinderErrorNotFound
     *
     * @dataProvider dataProviderLoading
     */
    public function testLoading($file)
    {
        $configuration = Configuration::get($file);

        // Array
        $this->assertTrue(is_array($configuration->testArray));
        $this->assertSame('value1', $configuration->testArray[0]);

        // Object
        $this->assertTrue(isset($configuration->testObject->property1));
        $this->assertSame('value2', $configuration->testObject->property2);

        // Test child configuration
        $this->assertInstanceOf(Configuration::class, $configuration->testObject);

        $this->assertNull($configuration->nonExistentKey);
        $this->assertFalse(isset($configuration->nonExistentKey));
        $this->assertFalse(isset($configuration->nonExistentKey->goingDeeper));
    }

    /**
     * @return array
     */
    public function dataProviderLoading()
    {
        return [
            'JSON' =>   ['test1.json'],
            'YAML' =>   ['test2.yml'],
        ];
    }

    /**
     * @throws ResourceErrorInvalidFileType
     * @throws ResourceFinderErrorNotFound
     */
    public function testYamlenv()
    {
        $configuration = Configuration::get(dirname(__DIR__) . '/../.config/' . Configuration::ENV_FILE);

        // Object
        $this->assertTrue(isset($configuration->testObject->property1));
        $this->assertSame('value2', $configuration->testObject->property2);

        // Test child configuration
        $this->assertInstanceOf(Configuration::class, $configuration->testObject);

        $this->assertNull($configuration->nonExistentKey);
        $this->assertFalse(isset($configuration->nonExistentKey));
        $this->assertFalse(isset($configuration->nonExistentKey->goingDeeper));

        // Environment vars are now set in uppercase
        $this->assertSame('value1', getenv('TESTOBJECT_PROPERTY1'));
        $this->assertSame('value2', getenv('TESTOBJECT_PROPERTY2'));
    }

    /**
     * @throws ResourceErrorInvalidFileType
     * @throws ResourceFinderErrorNotFound
     */
    public function testYamlenvRequiredException()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('One or more environment variables failed assertions: TESTOBJECT_PROPERTY1 is not an integer.');

        Configuration::yamlenv()->required('TESTOBJECT_PROPERTY1')->isInteger();
    }

}