<?php

namespace Gojiro;

use Gojiro\Field;

/**
 * CSVファイルのフィールドを管理
 */
class Fields
{
    /** @var fields フィールド名の配列 */
    protected $fields = [];

    public function __construct($fields = [])
    {
        $fields = array_filter($fields);
        $this->fields = $fields;
        foreach ($fields as $idx => $name) {
            $this->$name = new Field($idx, $name);
        }
    }

    /**
     * フィールド名一覧を配列で返す
     * @return array フィールド名一覧
     */
    public function list() : array
    {
        return $this->fields;
    }

    /**
     * 与えられたフィールド名（の配列）のみ有効にする。
     * それ以外は無効にする
     * @param $fields フィールド名の配列
     * @return Fields
     */
    public function select(array $fields = []) : Fields
    {
        // すべて無効化
        foreach ($this->fields as $name) {
            $this->$name->disable();
        }

        // 指定されたもののみ有効化
        foreach ($fields as $name) {
            if (@!is_null($this->$name)) {
                $this->$name->enable();
            }
        }

        return $this;
    }
}
