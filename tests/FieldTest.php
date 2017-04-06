<?php

namespace Gojiro\Test;

use PHPUnit\Framework\TestCase;

use Gojiro\Field;

class FieldTest extends TestCase
{
    private $target = null;

    public function setUp()
    {
        $this->target = new Field(99, "test");
    }

    public function testProperty()
    {
        $this->assertTrue($this->target->enable);
        $this->assertEquals(99, $this->target->idx);
        $this->assertEquals("test", $this->target->name);
    }

    public function testDisable()
    {
        // [Field::enable] true -> false
        $this->target->enable = true;
        $this->target->disable();
        $this->assertFalse($this->target->enable);

        // [Field::enable] false -> false
        $this->target->enable = false;
        $this->target->disable();
        $this->assertFalse($this->target->enable);
    }

    public function testEnable()
    {
        // [Field::enable] true -> true
        $this->target->enable = true;
        $this->target->enable();
        $this->assertTrue($this->target->enable);

        // [Field::enable] false -> true
        $this->target->enable = false;
        $this->target->enable();
        $this->assertTrue($this->target->enable);
    }
}
