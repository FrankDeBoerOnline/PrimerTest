<?php

namespace FrankDeBoerOnline\Configuration\tests;

use FrankDeBoerOnline\Configuration\Error\ResourceErrorInvalidFileType;
use FrankDeBoerOnline\Configuration\Resource;
use PHPUnit\Framework\TestCase;

class ResourceTest extends TestCase
{

    public function testConstruct()
    {
        // Auto-set type to json
        $resource = new Resource('../file.json');
        $this->assertSame('../file.json', $resource->getFileName());
        $this->assertSame(Resource::FILE_TYPE_JSON, $resource->getFileType());

        // Auto-set type to yml
        $resource = new Resource('../file.yml');
        $this->assertSame('../file.yml', $resource->getFileName());
        $this->assertSame(Resource::FILE_TYPE_YAML, $resource->getFileType());

        // Overwrite type
        $resource = new Resource('../file.json', Resource::FILE_TYPE_YAML);
        $this->assertSame('../file.json', $resource->getFileName());
        $this->assertSame(Resource::FILE_TYPE_YAML, $resource->getFileType());
    }

    /**
     * @throws ResourceErrorInvalidFileType
     */
    public function testInvalidFileTypeWithParam()
    {
        $this->expectException(ResourceErrorInvalidFileType::class);
        new Resource('../file.json', 'invalid');
    }

    /**
     * @throws ResourceErrorInvalidFileType
     */
    public function testInvalidFileTypeWithoutParam()
    {
        $this->expectException(ResourceErrorInvalidFileType::class);
        new Resource('../file.invalid');
    }

}