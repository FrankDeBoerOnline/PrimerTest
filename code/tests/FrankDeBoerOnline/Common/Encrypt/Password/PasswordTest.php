<?php

namespace Tests\FrankDeBoerOnline\Common\Encrypt\Password;

use FrankDeBoerOnline\Common\Encrypt\Password\Password;
use PHPUnit\Framework\TestCase;

class PasswordTest extends TestCase
{

    public function testGenerateHash()
    {
        $password = "test1234";
        $hash1 = Password::generateHash($password, 4);
        $this->assertTrue(Password::validate($password, $hash1));

        $hash2 = Password::generateHash($password, 4);
        // Make sure the hash is not the same as the previous
        $this->assertNotEquals($hash1, $hash2);
        $this->assertTrue(Password::validate($password, $hash2));
    }

    public function testValidate()
    {
        $password1 = "test1234";
        $password2 = "test1235";

        $hash1 = Password::generateHash($password1, 4);
        $hash2 = Password::generateHash($password2, 4);

        $this->assertTrue(Password::validate($password1, $hash1));
        $this->assertTrue(Password::validate($password2, $hash2));

        $this->assertFalse(Password::validate($password1, $hash2));
        $this->assertFalse(Password::validate($password2, $hash1));
    }

    public function testGenerateTemporaryPassword()
    {
        // Test with only lowercase
        $tempPassLowerCase = Password::generateTemporaryPassword(9, false, 'l');
        $this->assertTrue(preg_match("#^[a-z]{9}$#", $tempPassLowerCase) > 0);

        // Test with only uppercase
        $tempPassUpperCase = Password::generateTemporaryPassword(9, false, 'u');
        $this->assertTrue(preg_match("#^[A-Z]{9}$#", $tempPassUpperCase) > 0);

        // Test with only decimals
        $tempPassDecimals = Password::generateTemporaryPassword(9, false, 'd');
        $this->assertTrue(preg_match("#^[2-9]{9}$#", $tempPassDecimals) > 0);

        // Test with only special characters
        $tempPassSpecial = Password::generateTemporaryPassword(9, false, 's');
        $this->assertTrue(preg_match("#^[\!\@\#\$\%\&\*\?]{9}$#", $tempPassSpecial) > 0);
    }

}