<?php

use Cerad\Component\Security\PasswordEncoder;

class PasswordEncoderTest extends \PHPUnit_Framework_TestCase
{
  public function testPasswordEncorder()
  {
    $encoder = new PasswordEncoder('master');

    // Master
    $this->assertTrue($encoder->isPasswordValid('encoded','master'));

    // md5
    $this->assertTrue($encoder->isPasswordValid(md5('whatever'), 'whatever'));

    // Sha encoding
    $this->assertTrue($encoder->isPasswordValid(
      '9t9ufE4P/i6LJWQBdEByOTJnH4jONnQJq4ojhR/AJHRvSll20l2tqL8a+eh9U/YE1P0gVOYdMnQQ3jSQoAgQYA==',
      'zzz',
      'f5pqe5h7mps0g084kkgo0w0484csw0g'
    ));
    $this->assertFalse($encoder->isPasswordValid(
      '9t9ufE4P/i6LJWQBdEByOTJnH4jONnQJq4ojhR/AJHRvSll20l2tqL8a+eh9U/YE1P0gVOYdMnQQ3jSQoAgQYA==',
      'zzzx',
      'f5pqe5h7mps0g084kkgo0w0484csw0g'
    ));
  }
}