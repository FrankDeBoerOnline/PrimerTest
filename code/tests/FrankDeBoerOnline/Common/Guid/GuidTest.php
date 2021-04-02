<?php

namespace Tests\FrankDeBoerOnline\Common\Guid;

use FrankDeBoerOnline\Common\Guid\Guid;
use PHPUnit\Framework\TestCase;

class GuidTest extends TestCase
{

    CONST GUID_V5_EQUAL = '2d5c5390-351f-5bd3-af31-b377dced0a1b';
    CONST GUID_V5_DIFF = 'e7f2b2b7-812a-52ea-a6ac-b5bfd8ebac21';

    CONST GUID_V5_EQUAL_NAMESPACE_TEST = 'dfea99d9-6e94-5a65-8a45-0e7dbdf44fe3';

    public function testHash()
    {
        $this->assertEquals(self::GUID_V5_EQUAL, Guid::hash('equal'));
        $this->assertEquals(self::GUID_V5_DIFF, Guid::hash('different'));
        $this->assertEquals(self::GUID_V5_EQUAL, Guid::hash('equal'));

        // With different namespace
        $this->assertEquals(self::GUID_V5_EQUAL_NAMESPACE_TEST, Guid::hash('equal', 'test'));
        $this->assertEquals(self::GUID_V5_EQUAL_NAMESPACE_TEST, Guid::hash('equal', 'test'));
    }

    public function testValidGUID()
    {
        $v4 = Guid::random();
        $this->assertTrue(Guid::isValid($v4), 'Guid V4 is not valid');
        $this->assertTrue(Guid::isValid(self::GUID_V5_EQUAL), 'Guid V5 is not valid');
    }

    public function testRandom()
    {
        $hash1 = Guid::random();
        $hash2 = Guid::random();
        $this->assertNotEquals($hash1, $hash2);
        $this->assertNotEquals($hash1, Guid::random());
        $this->assertNotEquals($hash2, Guid::random());
        $this->assertNotEquals($hash1, Guid::random());
    }

    public function testBase58()
    {
        $base58guid = Guid::encodeBase58(self::GUID_V5_EQUAL);
        $this->assertSame('6bss1Qv6nQbpKyV2RrYRer', $base58guid);
        $this->assertSame(self::GUID_V5_EQUAL, Guid::decodeBase58($base58guid));
    }

}