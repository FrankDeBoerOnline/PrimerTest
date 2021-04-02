<?php

namespace FrankDeBoerOnline\Error;

use Throwable;

interface ErrorInterface extends Throwable
{

    /**
     * Get the set values for message. If namedIndex is omitted, the whole array is returned
     * 
     * @param string|null $namedIndex
     * @return array|string|null
     */
    public function getMessageVars($namedIndex = null);

}