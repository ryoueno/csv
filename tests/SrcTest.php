<?php

namespace Gojiro\Test;

use PHPUnit\Framework\TestCase;

use Gojiro\Src;

class SrcTest extends TestCase
{
    private $test_file_path = 'tests/test.csv';
    private $target = null;

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
        $this->target = new Src($this->test_file_path);
    }

    /**
     * テスト用に作成したCSVファイルを削除
     */
    public function tearDown()
    {
        unlink($this->test_file_path);
    }

    public function testGet()
    {
        $this->assertEquals(
            [
                ['id', 'name'],
                [1, 'apple'],
                [2, 'banana'],
                [3, 'orange'],
            ],
            $this->target->get()
        );

        $this->assertEquals(
            [
                [1, 'apple'],
                [2, 'banana'],
                [3, 'orange'],
            ],
            $this->target->get(true) // 1行目除外
        );
    }

    public function testEncode()
    {
        $encode = self::getMethod("\Gojiro\Src", "encode");
        $encoding_list = "ASCII, JIS, UTF-8, EUC-JP, SJIS";

        /* [JIS -> UTF-8] */
        $jis = mb_convert_encoding("文字列テスト_JIS", "JIS", $encoding_list);
        $this->assertEquals(
            "JIS",
            mb_detect_encoding($jis, $encoding_list)
        );
        // UTF-8 に変換
        $this->assertEquals(
            "UTF-8",
            mb_detect_encoding(
                $encode->invokeArgs($this->target, [$jis]),
                $encoding_list
            )
        );

        /* [SJIS -> UTF-8] */
        $sjis = mb_convert_encoding("文字列テスト_SJIS", "SJIS", $encoding_list);
        $this->assertEquals(
            "SJIS",
            mb_detect_encoding($sjis, $encoding_list)
        );
        // UTF-8 に変換
        $this->assertEquals(
            "UTF-8",
            mb_detect_encoding(
                $encode->invokeArgs($this->target, [$sjis]),
                $encoding_list
            )
        );

        /* [EUC-JP -> UTF-8] */
        $eucjp = mb_convert_encoding("文字列テスト_EUC-JP", "EUC-JP", $encoding_list);
        $this->assertEquals(
            "EUC-JP",
            mb_detect_encoding($eucjp, $encoding_list)
        );
        // UTF-8 に変換
        $this->assertEquals(
            "UTF-8",
            mb_detect_encoding(
                $encode->invokeArgs($this->target, [$eucjp]),
                $encoding_list
            )
        );

        /* [UTF-8 -> UTF-8] */
        $utf8 = mb_convert_encoding("文字列テスト_UTF-8", "UTF-8", $encoding_list);
        $this->assertEquals(
            "UTF-8",
            mb_detect_encoding($utf8, $encoding_list)
        );
        // UTF-8 に変換
        $this->assertEquals(
            "UTF-8",
            mb_detect_encoding(
                $encode->invokeArgs($this->target, [$utf8]),
                $encoding_list
            )
        );
    }

    /**
     * メソッドをAccessibleにする
     */
    protected function getMethod($class, $method)
    {
        $class = new \ReflectionClass($class);
        $method = $class->getMethod($method);
        $method->setAccessible(true);
        return $method;
    }
}
