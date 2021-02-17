<?php


namespace App\Tests\unit\Messenger;

use App\Messenger\ArrayMessageIn;
use App\Messenger\ArrayMessageOut;
use App\Tests\unit\UnitTestCase;

class ArrayMessageTest extends UnitTestCase
{
    public function testArrayMessageInDataIsCorrect() {
        $message = new ArrayMessageIn('testid',['value'=>'test']);

        $this->assertEquals($message->getId(),'testid');
        $this->assertEquals($message->getData(),['value'=>'test']);
    }

    public function testArrayMessageOutDataIsCorrect() {
        $message = new ArrayMessageOut('testid',['value'=>'test']);

        $this->assertEquals($message->getId(),'testid');
        $this->assertEquals($message->getData(),['value'=>'test']);
    }
}
