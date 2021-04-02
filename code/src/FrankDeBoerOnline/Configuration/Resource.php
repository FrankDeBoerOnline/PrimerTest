<?php

namespace FrankDeBoerOnline\Configuration;

use FrankDeBoerOnline\Configuration\Error\ResourceErrorInvalidFileType;

class Resource
{

    CONST FILE_TYPE_JSON = 'json';
    CONST FILE_TYPE_YAML = 'yml';

    /**
     * @var string
     */
    private $fileName;

    /**
     * @var string
     */
    private $fileType;

    /**
     * @param string $fileName
     * @param string|null $fileType
     * @throws ResourceErrorInvalidFileType
     */
    public function __construct($fileName, $fileType = null)
    {
        $this->setFileName($fileName);
        $this->setFileType($fileType ? $fileType : $this->getFileTypeFromFileExtension());
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     * @return $this
     */
    private function setFileName(string $fileName)
    {
        $this->fileName = $fileName;
        return $this;
    }

    /**
     * @return string
     */
    public function getFileType()
    {
        return $this->fileType;
    }

    /**
     * @param string $fileType
     * @return $this
     * @throws ResourceErrorInvalidFileType
     */
    private function setFileType(string $fileType)
    {
        if(!in_array($fileType, [self::FILE_TYPE_JSON, self::FILE_TYPE_YAML])) {
            throw new ResourceErrorInvalidFileType(['fileType' => $fileType]);
        }

        $this->fileType = $fileType;
        return $this;
    }

    /**
     * @return string|null;
     */
    private function getFileTypeFromFileExtension()
    {
        $fileParts = explode('.', $this->getFileName());
        $extension = array_pop($fileParts);
        return (string)$extension;
    }

}