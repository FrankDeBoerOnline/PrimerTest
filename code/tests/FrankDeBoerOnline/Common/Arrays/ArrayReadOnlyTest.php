<?php

namespace Tests\FrankDeBoerOnline\Common\Arrays;

use FrankDeBoerOnline\Common\Arrays\ArrayReadOnly;
use FrankDeBoerOnline\Common\Arrays\Error\ArrayReadOnlyError;
use PHPUnit\Framework\TestCase;

class ArrayReadOnlyTest extends TestCase
{

    /**
     * @return ArrayReadOnly
     */
    private function getDefaultArray()
    {
        return (
            new ArrayReadOnly(
                [
                    0 => 'Index0',
                    1 => 'Index1',
                    'namedIndex1' => 'NamedIndex 1',
                    'namedIndex2' => 'NamedIndex 2',
                ]
            )
        );
    }

    public function testDefaults()
    {
        $array = $this->getDefaultArray();

        // Test as this function should not work with ArrayObjects (expected!)
        $this->assertFalse(array_key_exists('namedIndex1', $array));

        $this->assertTrue(isset($array['namedIndex1']));
        $this->assertTrue(isset($array->namedIndex2));
        $this->assertSame(4, count($array));
    }

    public function testReadOnlyAddNew()
    {
        $this->expectException(ArrayReadOnlyError::class);

        $array = $this->getDefaultArray();
        $array['newKey'] = 'Add one';
    }

    public function testReadOnlyAddNewByProperty()
    {
        $this->expectException(ArrayReadOnlyError::class);

        $array = $this->getDefaultArray();
        $array->newKey = 'Add one';
    }

    public function testReadOnlyUnset()
    {
        $this->expectException(ArrayReadOnlyError::class);

        $array = $this->getDefaultArray();
        unset($array[0]);
    }

    public function testReadOnlyUnsetByProperty()
    {
        $this->expectException(ArrayReadOnlyError::class);

        $array = $this->getDefaultArray();
        unset($array->namedIndex1);
    }

    public function testReadOnlyUpdate()
    {
        $this->expectException(ArrayReadOnlyError::class);

        $array = $this->getDefaultArray();
        $array['namedIndex1'] = 'Update one';
    }

    public function testReadOnlyUpdateByProperty()
    {
        $this->expectException(ArrayReadOnlyError::class);

        $array = $this->getDefaultArray();
        $array->namedIndex1 = 'Update one';
    }

}