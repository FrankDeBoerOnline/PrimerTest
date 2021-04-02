<?php

namespace FrankDeBoerOnline\Common\Guid;

use FrankDeBoerOnline\Common\Guid\Error\GuidErrorInvalidGuid;

trait GuidIdentifier
{

    /**
     * @var string
     */
    private $guidIdentifier;

    /**
     * @return string|false
     */
    abstract protected function calculateGuidIdentifier();

    /**
     * @return string $guid
     */
    public function getGuidIdentifier()
    {
        if($this->guidIdentifier === null) {
            $this->calculateGuidIdentifier();
        }
        return $this->guidIdentifier;
    }

    /**
     * @param string $guidIdentifier
     * @return $this
     * @throws GuidErrorInvalidGuid
     */
    public function setGuidIdentifier($guidIdentifier)
    {
        if(!Guid::isValid($guidIdentifier)) {
            throw new GuidErrorInvalidGuid(['guidIdentifier' => $guidIdentifier]);
        }

        $this->guidIdentifier = $guidIdentifier;
        return $this;
    }

    /**
     * @param array $properties
     * @return string
     */
    public function getGuidIdentifierByProperties($properties)
    {
        $toBeHashed = json_encode($properties);
        return Guid::hash($toBeHashed);
    }

    /**
     * Do not use in-code names to generate guids!!
     * @param array $properties
     * @return $this
     * @throws GuidErrorInvalidGuid
     */
    public function setGuidIdentifierByProperties($properties)
    {
        return $this->setGuidIdentifier($this->getGuidIdentifierByProperties($properties));
    }

}