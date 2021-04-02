<?php

namespace FrankDeBoerOnline\Common\Guid;

use FrankDeBoerOnline\Common\Guid\Error\GuidErrorInvalidGuid;

interface GuidIdentifierInterface
{

    /**
     * @return string
     */
    public function getGuidIdentifier();

    /**
     * @param string $guid
     * @return $this
     * @throws GuidErrorInvalidGuid
     */
    public function setGuidIdentifier($guid);

    /**
     * @param array $properties
     * @return string
     */
    public function getGuidIdentifierByProperties($properties);

    /**
     * Do not use in-code names to generate guid's!!
     * @param array $properties
     * @return $this
     */
    public function setGuidIdentifierByProperties($properties);

}