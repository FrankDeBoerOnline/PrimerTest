<?php

namespace FrankDeBoerOnline\Common\Persist\Test;

use FrankDeBoerOnline\Common\BigNumber\StrictBigInt;
use FrankDeBoerOnline\Common\DateTime\DateTime;
use FrankDeBoerOnline\Common\DateTime\Error\DateTimeError;
use FrankDeBoerOnline\Common\Persist\Persistable;
use FrankDeBoerOnline\Common\Guid\GuidIdentifier;
use FrankDeBoerOnline\Common\Guid\Error\GuidErrorInvalidGuid;
use FrankDeBoerOnline\Common\Persist\Persisting;

class TestWithGuid implements Persistable
{

    use GuidIdentifier;
    use Persisting;

    /**
     * @var string
     */
    private $name;

    /**
     * @var StrictBigInt
     */
    private $amount;

    /**
     * @var DateTime
     */
    private $bookDate;

    /**
     * TestWithGuid constructor.
     * @param string $name
     * @param mixed $amount
     * @param mixed $bookDate
     * @throws DateTimeError
     */
    public function __construct($name, $amount, $bookDate)
    {
        $this->setName($name);
        $this->setAmount(new StrictBigInt($amount));
        $this->setBookDate(new DateTime($bookDate));
    }

    /**
     * @return TestWithGuidPersistMapper
     */
    public static function getPersistingMapper()
    {
        return (new TestWithGuidPersistMapper());
    }

    /**
     * @return TestWithGuid
     * @throws GuidErrorInvalidGuid
     */
    protected function calculateGuidIdentifier()
    {
        $guidProperties = [
            'name' => (string)$this->getName(),
        ];
        return $this->setGuidIdentifierByProperties($guidProperties);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    protected function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return StrictBigInt
     */
    public function getAmount()
    {
        return clone $this->amount;
    }

    /**
     * @param StrictBigInt $amount
     * @return $this
     */
    protected function setAmount(StrictBigInt $amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getBookDate()
    {
        return clone $this->bookDate;
    }

    /**
     * @param DateTime $bookDate
     * @return $this
     */
    protected function setBookDate(DateTime $bookDate)
    {
        $this->bookDate = $bookDate;
        return $this;
    }

}