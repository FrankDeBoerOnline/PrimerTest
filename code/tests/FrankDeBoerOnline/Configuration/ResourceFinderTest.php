<?php

namespace FrankDeBoerOnline\Configuration\tests;

use FrankDeBoerOnline\Configuration\Configuration;
use FrankDeBoerOnline\Configuration\Error\ResourceErrorInvalidFileType;
use FrankDeBoerOnline\Configuration\Error\ResourceFinderErrorNotFound;
use FrankDeBoerOnline\Configuration\ResourceFinder;
use PHPUnit\Framework\TestCase;

class ResourceFinderTest extends TestCase
{

    /**
     * @throws ResourceFinderErrorNotFound
     * @throws ResourceErrorInvalidFileType
     */
    public function testFind()
    {
        $resource = ResourceFinder::find('test1.json');
        $this->assertContains('/tests/.config/test1.json', $resource->getFileName());
    }

    /**
     * @throws ResourceErrorInvalidFileType
     * @throws ResourceFinderErrorNotFound
     */
    public function testFindNotFound()
    {
        $this->expectException(ResourceFinderErrorNotFound::class);
        ResourceFinder::find('test3.json');
    }

    /**
     * @throws ResourceFinderErrorNotFound
     */
    public function testGetRealPath()
    {
        $filename = ResourceFinder::getRealPath(__DIR__. '/../../.config/test1.json');
        $this->assertContains('/tests/.config/test1.json', $filename);
    }

    /**
     * @throws ResourceFinderErrorNotFound
     */
    public function testGetRealPathNotFound()
    {
        $this->expectException(ResourceFinderErrorNotFound::class);
        ResourceFinder::getRealPath('doesNotExist');
    }

    /**
     * @throws ResourceErrorInvalidFileType
     * @throws ResourceFinderErrorNotFound
     */
    public function testCustomerDirectories()
    {
        try {
            ResourceFinder::find('test3.yml');
            $this->fail('It should not find this file');

        } catch(ResourceFinderErrorNotFound $e) {}

        ResourceFinder::addConfigDirectory(__DIR__ . '/../../.config/different_path');

        $filename = ResourceFinder::find('test3.yml');
        $this->assertTrue((bool)$filename);
    }

}