<?php

namespace Gojiro\Test;

use PHPUnit\Framework\TestCase;

use Gojiro\Csv;
use Gojiro\Src;

class CsvTest extends TestCase
{
    public $target = null;
    private $test_file_path = 'tests/test.csv';

    /**
     * オブジェクトの準備、テスト用のCSVファイルを作成
     */
    public function setUp()
    {
        $data = [
            ['id', 'name'],
            [1, 'apple'],
            [2, 'banana'],
            [3, 'orange'],
        ];
        $fp = fopen($this->test_file_path, 'w');
        foreach($data as $row){
            $line = implode(',' , $row);
            fwrite($fp, $line . "\n");
        }
        fclose($fp);

        $this->target = new Csv(
            new Src($this->test_file_path)
        );
    }

    /**
     * テスト用に作成したCSVファイルを削除
     */
    public function tearDown()
    {
        unlink($this->test_file_path);
    }

    public function testSelect()
    {
        $this->target->select([]);
        $this->assertFalse($this->target->fields->id->enabled);
        $this->assertFalse($this->target->fields->name->enabled);

        $this->target->select(['id']);
        $this->assertTrue($this->target->fields->id->enabled);
        $this->assertFalse($this->target->fields->name->enabled);

        $this->target->select(['id', 'name']);
        $this->assertTrue($this->target->fields->id->enabled);
        $this->assertTrue($this->target->fields->name->enabled);

        $this->target->select(['id', 'name', 'test']);
        $this->assertTrue($this->target->fields->id->enabled);
        $this->assertTrue($this->target->fields->name->enabled);
    }

    public function testWhere()
    {
        $disable_rows = self::getProperty('\Gojiro\Csv', 'disable_rows');

        $disable_rows->setValue($this->target, []);
        $this->target->where([]);
        $this->assertEquals(
            [],
            $disable_rows->getValue($this->target)
        );

        $disable_rows->setValue($this->target, []);
        $this->target->where(['id' => 3]);
        $this->assertEquals(
            [0, 1],
            $disable_rows->getValue($this->target)
        );

        $disable_rows->setValue($this->target, []);
        $this->target->where(['name' => 'banana']);
        $this->assertEquals(
            [0, 2],
            $disable_rows->getValue($this->target)
        );

        $disable_rows->setValue($this->target, []);
        $this->target->where(['id' => 999]);
        $this->assertEquals(
            [0, 1, 2],
            $disable_rows->getValue($this->target)
        );

        $disable_rows->setValue($this->target, []);
        $this->target->where(['test' => 'test']);
        $this->assertEquals(
            [],
            $disable_rows->getValue($this->target)
        );
    }

    public function testGet()
    {
        $disable_rows = self::getProperty('\Gojiro\Csv', 'disable_rows');
        $disable_rows->setValue($this->target, []);

        $this->assertEquals(
            [
                0 => [
                    'id'   => 1,
                    'name' => 'apple',
                ],
                1 => [
                    'id'   => 2,
                    'name' => 'banana',
                ],
                2 => [
                    'id'   => 3,
                    'name' => 'orange',
                ],
            ],
            $this->target->get()
        );

        $disable_rows->setValue($this->target, [0,1]);
        $this->target->fields->id->enabled = false;

        $this->assertEquals(
            [
                2 => [
                    'name' => 'orange',
                ],
            ],
            $this->target->get()
        );

        $disable_rows->setValue($this->target, []);
        $this->target->fields->id->enabled = false;
        $this->target->fields->name->enabled = false;

        $this->assertEquals(
            [],
            $this->target->get()
        );
    }

    protected static function getProperty($class, $name)
    {
        $class = new \ReflectionClass($class);

        $property = $class->getProperty($name);
        $property->setAccessible(true);

        return $property;
    }
}
