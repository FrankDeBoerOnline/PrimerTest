<?php

namespace FrankDeBoerOnline\Common\Guid;

use FrankDeBoerOnline\Common\Encoding\Encoding;
use Rhumsaa\Uuid\Uuid;

class Guid
{

    /**
     * WARNING: Never change this number unless you know what you are doing!!!!
     * If changed, it will affect all past GUIDs ever generated by this class
     */
    CONST MASTER_GUID = '6a7c84a3-9bca-4ece-b67b-159f5e78b9cd';


    static public function isValid($guid)
    {
        return Uuid::isValid($guid);
    }

    /**
     * Get a random guid (UUID V4)
     * @return string
     */
    static public function random()
    {
        return (string)Uuid::uuid4();
    }

    /**
     * Get guid for input (UUID V5)
     * @param string $input
     * @param string $namespace
     * @return string
     */
    static public function hash($input, $namespace = '')
    {
        $namespace = ($namespace ? self::hash($namespace) : self::MASTER_GUID);
        return (string)Uuid::uuid5($namespace, $input);
    }

    /**
     * @param string $guid
     * @return string
     */
    static public function encodeBase58($guid)
    {
        $uuid = Uuid::fromString($guid);
        $uuidBytes = $uuid->getBytes();
        return Encoding::encodeBase58($uuidBytes);
    }

    /**
     * @param string $encodedGuid
     * @return string
     */
    static public function decodeBase58($encodedGuid)
    {
        $decoded = Encoding::decodeBase58($encodedGuid);
        $uuid = Uuid::fromBytes($decoded);
        return (string)$uuid;
    }

}