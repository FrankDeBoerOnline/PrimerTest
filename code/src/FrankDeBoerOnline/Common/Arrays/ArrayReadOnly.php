<?php

namespace FrankDeBoerOnline\Common\Arrays;

use ArrayObject;
use FrankDeBoerOnline\Common\Arrays\Error\ArrayReadOnlyError;

/**
 *
 * An implementation of arrays where it can be created and not changed
 *
 * array functions known NOT to work with ArrayObjects
 * array_key_exists
 * array_keys
 *
 * Class ArrayReadOnly
 * @package FrankDeBoerOnline\Common\Arrays
 */
class ArrayReadOnly extends ArrayObject
{

    public function __construct($input = array(), $flags = 0, $iterator_class = "ArrayIterator")
    {
        parent::__construct(
            $input,
            ($flags ? $flags : ArrayObject::STD_PROP_LIST | ArrayObject::ARRAY_AS_PROPS),
            $iterator_class
        );
    }

    /**
     * Sets the value at the specified index to newval
     * @link https://php.net/manual/en/arrayobject.offsetset.php
     * @param mixed $index <p>
     * The index being set.
     * </p>
     * @param mixed $newval <p>
     * The new value for the <i>index</i>.
     * </p>
     * @return void
     * @throws ArrayReadOnlyError
     * @since 5.0
     */
    public function offsetSet($index, $newval)
    {
        throw new ArrayReadOnlyError();
    }

    /**
     * Unsets the value at the specified index
     * @link https://php.net/manual/en/arrayobject.offsetunset.php
     * @param mixed $index <p>
     * The index being unset.
     * </p>
     * @return void
     * @throws ArrayReadOnlyError
     * @since 5.0
     */
    public function offsetUnset($index)
    {
        throw new ArrayReadOnlyError();
    }

}