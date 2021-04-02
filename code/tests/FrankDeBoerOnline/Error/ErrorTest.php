<?php

namespace Tests\FrankDeBoerOnline\Error;

use PHPUnit\Framework\TestCase;

class ErrorTest extends TestCase
{

    public function testDefault()
    {
        $e = new TestErrorWithVars();
        $this->assertSame('Replacing var1 with \'{$var1}\' and var2 with \'{$var2}\'.', $e->getMessage());
        $this->assertSame(2, $e->getCode());
    }

    public function testCustomParameters()
    {
        $e = new TestErrorWithVars('Custom message', 34);
        $this->assertSame('Custom message', $e->getMessage());
        $this->assertSame(34, $e->getCode());
    }

    public function testMessageWithVars()
    {
        $e = new TestErrorWithVars([
            'var1' => 'Replacement1',
            'var2' => 'Replacement2'
        ]);

        $this->assertSame('Replacing var1 with \'Replacement1\' and var2 with \'Replacement2\'.', $e->getMessage());
    }

    public function testGetMessageVars()
    {
        $e = new TestErrorWithVars([
            'var1' => 'Replacement1',
            'var2' => 'Replacement2'
        ]);

        $this->assertTrue(count($e->getMessageVars()) === 2);
        $this->assertSame('Replacement1', $e->getMessageVars('var1'));
        $this->assertSame('Replacement2', $e->getMessageVars('var2'));
        $this->assertNull($e->getMessageVars('var3'));
    }

}