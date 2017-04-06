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
        $this->assertTrue($this->target->enabled);
        $this->assertEquals(99, $this->target->idx);
        $this->assertEquals("test", $this->target->name);
    }

    public function testDisable()
    {
        // [Field::enabled] true -> false
        $this->target->enabled = true;
        $this->target->disable();
        $this->assertFalse($this->target->enabled);

        // [Field::enabled] false -> false
        $this->target->enabled = false;
        $this->target->disable();
        $this->assertFalse($this->target->enabled);
    }

    public function testEnable()
    {
        // [Field::enabled] true -> true
        $this->target->enabled = true;
        $this->target->enable();
        $this->assertTrue($this->target->enabled);

        // [Field::enabled] false -> true
        $this->target->enabled = false;
        $this->target->enable();
        $this->assertTrue($this->target->enabled);
    }
}
