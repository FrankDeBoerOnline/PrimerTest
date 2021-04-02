<?php

namespace FrankDeBoerOnline\Common\Arrays;

class ArrayFunctions
{

    /**
     * Pick a random value from a numeric indexed array
     * @param array $array
     * @return mixed|null
     */
    static public function pickRandom(array $array)
    {

        // Make sure we have values to choose from
        if(!$array || !count($array)) {
            return null;
        }

        // Pick a random index
        $key = rand(0, count($array)-1);

        // Return the value of that index
        return $array[$key];
    }

    /**
     * Pick a random value from array
     * @param array $array
     * @return mixed|null
     */
    static public function pickRandomFromNamedIndexes(array $array)
    {

        // Make sure we have values to choose from
        if(!$array || !count($array)) {
            return null;
        }

        // As we do not know if the array has numeric indexes or named, just collect them
        $keys = array_keys($array);

        // Pick a random key
        $randomKey = self::pickRandom($keys);

        // Return the value on that index
        return $array[$randomKey];
    }

}