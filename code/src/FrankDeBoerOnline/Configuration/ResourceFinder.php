<?php

namespace FrankDeBoerOnline\Configuration;

use FrankDeBoerOnline\Configuration\Resource;
use FrankDeBoerOnline\Configuration\Error\ResourceFinderErrorNotFound;
use FrankDeBoerOnline\Configuration\Error\ResourceErrorInvalidFileType;

class ResourceFinder
{

    /**
     * @var string[]
     */
    static private $customerDirectories = [];

    /**
     * @param string $directory
     * @return void
     * @throws ResourceFinderErrorNotFound
     */
    static public function addConfigDirectory($directory)
    {
        if(file_exists($directory) && is_dir($directory)) {
            self::$customerDirectories[] = $directory;
            return;
        }

        throw new ResourceFinderErrorNotFound();
    }

    /**
     * @param string $resourceName
     * @param string|null $fileType
     * @return Resource
     * @throws ResourceErrorInvalidFileType
     * @throws ResourceFinderErrorNotFound
     */
    static public function find($resourceName, $fileType = null)
    {
        $filename = self::getRealPath($resourceName, self::getDirectories());
        $resource = new Resource($filename, $fileType);
        return $resource;
    }

    /**
     * @return string[]
     */
    static public function getDirectories()
    {
        return self::$customerDirectories;
    }

    /**
     * @param string $resourceName
     * @param string[] $directories
     * @return string
     * @throws ResourceFinderErrorNotFound
     */
    static public function getRealPath($resourceName, $directories = [])
    {
        // Test if resourceName is already a correct filepath
        if(file_exists($resourceName)) {
            return realpath($resourceName);
        }

        foreach ((array)$directories as $directory) {
            $filepath = $directory . '/'. $resourceName;
            if(file_exists($filepath)) {
                return realpath($filepath);
            }
        }

        throw new ResourceFinderErrorNotFound(['resourceName' => $resourceName]);
    }

}