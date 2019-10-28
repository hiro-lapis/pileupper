<?php
  require_once(dirname(__FILE__).'function.php');

  class FunctionTest extends PHPUnit\Framework\TestCase {

    public function testValidEmailFalse() {
      validEmail('aaa', 'email');
      $results = getErrMsg('email');
      $this->assertEquals($results);
    }

    public function testValidEmailTrue() {
      validEmail('sample@email.com', 'email');
      $results = getErrMsg('email');
      $this->assertNull($results);
    }

  }
