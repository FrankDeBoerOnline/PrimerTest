<?php

namespace FrankDeBoerOnline\Common\Encoding;

use Tuupola\Base58;

class Encoding
{

    CONST BASE_58 = 58;
    CONST BASE_64 = 64;

    /**
     * @param int $base
     * @param string $input
     * @return string
     */
    static public function encode($base, $input)
    {
        switch($base) {
            case self::BASE_58:
                return self::encodeBase58($input);
            case self::BASE_64:
                return self::encodeBase64($input);
        }

        return null;
    }

    /**
     * @param string $base
     * @param string $input
     * @return string
     */
    static public function decode($base, $input)
    {
        switch($base) {
            case self::BASE_58:
                return self::decodeBase58($input);
            case self::BASE_64:
                return self::decodeBase64($input);
        }

        return null;
    }




    static public function encodeBase58($input){

        $encoder = new Base58(["characters" => Base58::BITCOIN]);
        return $encoder->encode($input);
    }

    static public function decodeBase58($input){
        $encoder = new Base58(["characters" => Base58::BITCOIN]);
        return $encoder->decode($input);
    }

    static public function encodeBase64($input)
    {
        return base64_encode($input);
    }
    static public function decodeBase64($input)
    {
        return base64_decode($input);
    }

}