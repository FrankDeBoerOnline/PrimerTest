<?php

namespace FrankDeBoerOnline\Error;

use Exception;
use Throwable;

/**
 * This class functions as a wrapper and extension on the default exception class of PHP
 *
 * Class Error
 * @package FrankDeBoerOnline\Error
 */
class Error extends Exception implements ErrorInterface
{

    CONST ERROR_CODE = 0;
    CONST ERROR_MESSAGE = 'Unknown Error';

    CONST MESSAGE_VAR_FORMAT = '{$%s}';

    private $messageVars = [];

    /**
     * Construct the exception. Note: The message is NOT binary safe.
     * If message is an array, the named indexes are used to replace variables in the constant ERROR_MESSAGE
     * @link https://php.net/manual/en/exception.construct.php
     * @param string|array $message [optional] The Exception message to throw.
     * @param int $code [optional] The Exception code.
     * @param Throwable $previous [optional] The previous throwable used for the exception chaining.
     * @since 5.1
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {

        if(is_array($message)) {
            $this->setMessageVars($message);
            $description = $this->getMessageFormattedWithVars();

        } else {
            $description = ($message ? $message : $this::ERROR_MESSAGE);
        }

        parent::__construct(
            $description,
            ($code ? $code : $this::ERROR_CODE),
            $previous
        );
    }

    public function getMessageVars($namedIndex = null)
    {
        if($namedIndex) {
            return (isset($this->messageVars[$namedIndex]) ? $this->messageVars[$namedIndex] : null);
        }

        return $this->messageVars;
    }

    /**
     * @param string[] $messageVars
     * @return void
     */
    private function setMessageVars($messageVars = [])
    {
        foreach($messageVars as $namedIndex => $value) {
            if(trim($namedIndex)) {
                $this->messageVars[trim($namedIndex)] = (string)$value;
            }
        }

        return;
    }

    /**
     * @return string
     */
    private function getMessageFormattedWithVars()
    {
        $search = [];
        $replacement = [];
        foreach($this->getMessageVars() as $namedIndex => $value) {
            $search[] = sprintf($this::MESSAGE_VAR_FORMAT, $namedIndex);
            $replacement[] = $value;
        }

        return str_replace($search, $replacement, $this::ERROR_MESSAGE);
    }

}
