<?php

namespace Gojiro;

/**
 * 展開したCSVファイルを配列情報として管理する
 */
class Src
{
    /** @var array $data  Csvファイルを展開した配列 */
    protected $data = [];

    public function __construct($path)
    {
        try {
            $file = new \SplFileObject($path);
            $file->setFlags(
                \SplFileObject::READ_CSV |
                \SplFileObject::READ_AHEAD |
                \SplFileObject::SKIP_EMPTY |
                \SplFileObject::DROP_NEW_LINE
            );
        } catch (\RuntimeException $e) {
            throw $e;
        }
        if ($file !== []) {
            // エンコードして配列に格納
            foreach ($file as $row) {
                $row = $this->encode($row);
                $this->data[] = $row;
            }
        }
    }

    /**
     * Csvファイルを展開した配列を返す
     * @param bool $exclude_firlst 1行目を除外するかどうか
     * @return Array
     */
    public function get($exclude_first = false) : array
    {
        $data = $this->data;
        if ($exclude_first === true) {
            array_shift($data);
        }
        return $data;
    }

    /**
     * データをutf8に変換
     * @param  Array | string $data
     * @return Array | string
     */
    protected function encode($data)
    {
        if (is_array($data)) {
            return array_map(function ($d){
                return mb_convert_encoding($d, "utf-8", "ASCII, JIS, UTF-8, EUC-JP, SJIS");
            }, $data);
        } else {
            return mb_convert_encoding($data, "utf-8", "ASCII, JIS, UTF-8, EUC-JP, SJIS");
        }
    }
}