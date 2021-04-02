<?php

namespace FrankDeBoerOnline\Configuration;

use FrankDeBoerOnline\Configuration\Resource;
use FrankDeBoerOnline\Configuration\Error\ResourceErrorInvalidFileType;
use FrankDeBoerOnline\Configuration\Error\ResourceFinderErrorNotFound;
use FrankDeBoerOnline\Common\Arrays\ArrayReadOnly;
use FrankDeBoerOnline\Configuration\Yamlenv\Yamlenv;
use Symfony\Component\Yaml\Yaml;
use Dotenv\Dotenv;

class Configuration extends ArrayReadOnly
{

    CONST ENV_FILE = '.env.yml';

    /**
     * @var bool
     */
    static public $dotenv_loaded = false;

    /**
     * @var Yamlenv
     */
    static private $_yamlenv;

    /**
     * @var array
     */
    static private $_config = [];

    /**
     * @return string|bool
     */
    static public function getAppConfigDir()
    {
        $directories = ResourceFinder::getDirectories();
        foreach($directories as $directory) {
            if(file_exists($directory) && is_dir($directory)) {
                return realpath($directory);
            }
        }

        return false;
    }

    static public function getPackageConfigDir($packageName)
    {
        $appConfigDir = self::getAppConfigDir();
        if($appConfigDir === false) {
            return false;
        }

        $presumedPackageDir = $appConfigDir . '/../vendor/FrankDeBoerOnline/' . $packageName . '/.config';
        if(file_exists($presumedPackageDir) && is_dir($presumedPackageDir)) {
            return realpath($presumedPackageDir);
        }

        return $appConfigDir;
    }

    /**
     * @param string $resourceName
     * @param string|null $fileType
     * @return Configuration
     * @throws ResourceErrorInvalidFileType
     * @throws ResourceFinderErrorNotFound
     */
    static public function get($resourceName = '', $fileType = null)
    {
        return (new static([], $resourceName, $fileType));
    }

    /**
     * @return Yamlenv
     * @throws ResourceErrorInvalidFileType
     * @throws ResourceFinderErrorNotFound
     */
    static public function yamlenv()
    {
        // Load environment vars if not set
        if(!isset(self::$_yamlenv)) {
            (new static());
        }

        return self::$_yamlenv;
    }

    /**
     * Configuration constructor.
     * @param array $array
     * @param string $resourceName
     * @param string|null $fileType
     * @throws ResourceErrorInvalidFileType
     * @throws ResourceFinderErrorNotFound
     */
    public function __construct(array $array = [], $resourceName = '', $fileType = null)
    {

        // Always load DotEnv (or at least try it)
        $this->loadDotEnv();

        if($array) {
            parent::__construct($array);

        // Load different resource
        } else if ($resourceName) {
            parent::__construct(
                $this->loadResource(
                    ResourceFinder::find($resourceName, $fileType)
                )
            );

        // Load default environment file
        } else {
            parent::__construct(
                $this->loadResource(
                    ResourceFinder::find(self::ENV_FILE, Resource::FILE_TYPE_YAML)
                )
            );
        }
    }

    /**
     * @param mixed $name
     * @return Configuration|mixed|null
     */
    public function offsetGet($name)
    {
        try {
            $value = parent::offsetGet($name);
        } catch (\Exception $e) {
            $value = null;
        }

        if(!$value) {
            return null;
        }

        if(!is_array($value)) {
            return $value;
        }

        // Test if array has numeric keys
        if(isset($value[0])) {
            return $value;
        }

        return new $this($value);
    }

    /**
     * @param Resource $resource
     * @return array
     */
    private function loadResource(Resource $resource)
    {
        if(isset(self::$_config[$resource->getFileName()])) {
            return self::$_config[$resource->getFileName()];
        }

        $configData = [];
        switch($resource->getFileType()) {
            case Resource::FILE_TYPE_JSON:
                $configData = $this->loadJson($resource->getFileName());
                break;
            case Resource::FILE_TYPE_YAML:
                $configData = $this->loadYaml($resource->getFileName());
                break;
        }
        self::$_config[$resource->getFileName()] = (array)$configData;

        return self::$_config[$resource->getFileName()];
    }

    /**
     * @param string $filename
     * @return array
     */
    private function loadJson($filename)
    {
        return json_decode(file_get_contents($filename), true);
    }

    /**
     * @param string $filename
     * @return array
     */
    private function loadYaml($filename)
    {
        $result = Yaml::parseFile($filename);

        // Load environment vars
        $regex = "#/".preg_quote(self::ENV_FILE)."$#";
        if(preg_match($regex, $filename)) {
            $this->loadYamlenv($filename);
        }

        return $result;
    }

    /**
     * @param string $filename
     */
    private function loadYamlenv($filename)
    {
        self::$_yamlenv = new Yamlenv(dirname($filename), basename($filename), true);
        self::$_yamlenv->load();
    }

    private function loadDotEnv()
    {
        if(!self::$dotenv_loaded) {
            if(getenv('ENV_FILE_LOCATION')) {
                self::$dotenv_loaded = true; // Only do this once
                $dotEnv = Dotenv::create(dirname(getenv('ENV_FILE_LOCATION')));
                $dotEnv->load();
            }
        }
    }

}