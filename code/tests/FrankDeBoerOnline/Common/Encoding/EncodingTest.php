<?php

namespace Tests\FrankDeBoerOnline\Common\Encoding;

use FrankDeBoerOnline\Common\Encoding\Encoding;
use PHPUnit\Framework\TestCase;

class EncodingTest extends TestCase
{

    public function testBase58()
    {

        $input = 'TestBase58';
        $decodedInput = Encoding::encode(Encoding::BASE_58, $input);
        $this->assertSame('5k1XmK53Ta1GUo', $decodedInput);
        $this->assertSame($input, Encoding::decode(Encoding::BASE_58, $decodedInput));
    }

    public function testBase64()
    {

        $input = 'TestBase64';
        $decodedInput = Encoding::encode(Encoding::BASE_64, $input);
        $this->assertSame('VGVzdEJhc2U2NA==', $decodedInput);
        $this->assertSame($input, Encoding::decode(Encoding::BASE_64, $decodedInput));
    }

}