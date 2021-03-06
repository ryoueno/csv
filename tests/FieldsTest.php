<?php

namespace Gojiro\Test;

use PHPUnit\Framework\TestCase;

use Gojiro\Fields;

class FieldsTest extends TestCase
{
    public $target = null;

    public function setUp()
    {
        $this->target = new Fields(['id', 'name']);
    }

    public function testList()
    {
        $this->assertEquals(
            ['id', 'name'],
            $this->target->list()
        );
    }

    public function testSelect()
    {
        $this->target->select([]);
        $this->assertFalse($this->target->id->enabled);
        $this->assertFalse($this->target->name->enabled);

        self::setUp();

        $this->target->select(['id']);
        $this->assertTrue($this->target->id->enabled);
        $this->assertFalse($this->target->name->enabled);

        self::setUp();

        $this->target->select(['id', 'name']);
        $this->assertTrue($this->target->id->enabled);
        $this->assertTrue($this->target->name->enabled);

        self::setUp();

        $this->target->select(['id', 'name', 'test']);
        $this->assertTrue($this->target->id->enabled);
        $this->assertTrue($this->target->name->enabled);
    }
}
