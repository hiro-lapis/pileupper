<?php
  require_once(dirname(__FILE__).'/../function.php');

  class FunctionTest extends PHPUnit\Framework\TestCase {

    //1
    public function testValidRequiredTrue() {
      validRequired('テスト', 'case1');
      $results = getErrMsg('case1');
      $this->assertNull($results);
    }
    //2
    public function testValidRequiredFalse() {
      validRequired('', 'case2');
      $results = getErrMsg('case2');
      $this->assertEquals(MSG01, $results);
    }

    //3
    public function testValidEmailTrue() {
      validEmail('sample@email.com', 'case3');
      $results = getErrMsg('case3');
      $this->assertNull($results);
    }

    //4
    public function testValidEmailFalse() {
      validEmail('aaa', 'case4');
      $results = getErrMsg('case4');
      $this->assertEquals(MSG02, $results);
    }

    //5
    public function testValidLengthTrue() {
      validLength('abcdef', 'case5');
      $results = getErrMsg('case5');
      $this->assertNull($results);
    }

    //6
    public function testValidLengthFalse() {
      validLength('1234', 'case6');
      $results = getErrMsg('case6');
      $this->assertEquals(MSG03, $results);
    }

    //7
    public function testValidLengthFalse2() {
      validLength('012346789012345678901', 'case7');
      $results = getErrMsg('case7');
      $this->assertEquals(MSG03, $results);
    }

    //8
    public function testValidMaxLenTrue() {
      validMaxLen('18char@email.ne.jp', 'case8', 18);
      $results = getErrMsg('case8');
      $this->assertNull($results);
    }

    //9
    public function testValidMaxLenFalse() {
      validMaxLen('sample', 'case9', 4);
      $results = getErrMsg('case9');
      $this->assertEquals(MSG06a.'4'.MSG06b, $results);
    }
    //10
    public function testValidMaxLenFalse2() {
      validMaxLen('テスト', 'case10', 4);
      $results = getErrMsg('case9');
      $this->assertEquals(MSG06a.'4'.MSG06b, $results);
    }

    //11
    public function testValidMaxMbLenTrue() {
      validMaxMbLen('テスト', 'case11', 3);
      $results = getErrMsg('case11');
      $this->assertNull($results);
    }

    //12
    public function testValidMaxMbLenFalse() {
      validMaxMbLen('サンプル', 'case12', 3);
      $results = getErrMsg('case12');
      $this->assertEquals(MSG06a.'3'.MSG06b, $results);
    }

    //13
    public function testValidHalfTrue() {
      validHalf('sample', 'case13');
      $results = getErrMsg('case13');
      $this->assertNull($results);
    }

    //14
    public function testValidHalfFalse() {
      validHalf('サンプル', 'case14');
      $results = getErrMsg('case14');
      $this->assertEquals(MSG04, $results);
    }

    //15
    public function testValidHalfFalse2() {
      validHalf('１２３４５', 'case15');
      $results = getErrMsg('case15');
      $this->assertEquals(MSG04, $results);
    }

    //16
    public function testValidMatchTrue() {
      validMatch('sample', 'sample', 'case16');
      $results = getErrMsg('case16');
      $this->assertNull($results);
    }

    //17
    public function testValidMatchFalse() {
      validMatch('sample', 'SAMPLE', 'case17');
      $results = getErrMsg('case17');
      $this->assertEquals(MSG05, $results);
    }

    //18
    public function testvalidSelectTrue() {
      validSelect('1', 'case18');
      $results = getErrMsg('case18');
      $this->assertNull($results);
    }

    //19
    public function testValidSelectTrue2() {
      validSelect('10', 'case19');
      $results = getErrMsg('case19');
      $this->assertNull($results);
    }

    //20
    public function testValidSelectFalse() {
      validSelect('a', 'case20');
      $results = getErrMsg('case20');
      $this->assertEquals(MSG16, $results);
    }

    //21
    public function testValidEmailDupTrue() {
      validEmailDup('sample@sample.ne.jp', 'case21');
      $results = getErrMsg('case21');
      $this->assertNull($results);
    }

    //22**登録済みのEmailでエラーメッセージが入っていない。実際の動作確認ではエラー確認
    public function testValidEmailDupFalse() {
      validEmailDup('test@test.ne.jp', 'case22');
      $results = getErrMsg('case22');
      $this->assertEquals(MSG08, $results);
    }

    //23
    public function testgetUserReturnsDbData() {
      $results = getUser(21);
      $this->assertIsArray($results);
    }

    //24
    public function testgetUserReturnsDbDataFalse() {
      $results = getUser(1000);
      $this->assertFalse($results);
    }

    //25
    public function testgetTodoReturnsDbDataFalse() {
      $results = getToDo(7);
      $this->assertIsArray($results);
    }




  }
