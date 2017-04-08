<?php

namespace Gojiro;

use Gojiro\Src;
use Gojiro\Fields;

/**
 * CSVを管理する
 */
class Csv
{
    /** @var Src csvデータ(加工しない) */
    public $src;

    /** @var Fields フィールド(csvデータの一行目から生成) */
    public $fields = [];

    /** @var Array 絞り込みによって採用されない行の番号 */
    protected $disable_rows = [];


    public function __construct(Src $src)
    {
        $this->src = $src;
        $this->fields = new Fields(
            $src->get() === [] ? [] : $src->get()[0] // 1行目をフィールド扱い
        );
    }

    /**
     * $fields->enabled, $disable_rows を確認し、有効なデータのみ収集して返す
     *
     * @return Array
     */
    public function get() : array
    {
        $data = [];
        foreach ($this->fields->list() as $field_name) {
            if ($this->fields->$field_name->enabled === true) {
                // 有効なフィールドのみ
                foreach ($this->src->get(true) as $idx => $row) {
                    if (!in_array($idx, $this->disable_rows)) {
                        // 有効な行のみ
                        $data[$idx][$field_name] = $row[$this->fields->$field_name->idx];
                    }
                }
            }
        }
        $this->disable_rows = [];
        return $data;
    }

    /**
     * 指定されたカラムのみ有効にする
     * @return Csv
     */
    public function select(array $fields) : Csv
    {
        $this->fields->select($fields);
        return $this;
    }

    /**
     * 絞り込み条件を設定する
     * 存在しないものは削除しておく
     * @param array $conditions 絞り込み条件の配列 E.g. ['id' => 1]
     * @return Csv
     */
    public function where(array $conditions = []) : Csv
    {
        foreach ($conditions as $field_name => $value) {
            if (in_array($field_name, $this->fields->list())) {
                // フィールド名(Key)が存在する場合、値の一致を調査
                foreach ($this->src->get(true) as $row_num => $row) {
                    if ($row[$this->fields->$field_name->idx] != $value) {
                        $this->disable_rows[] = $row_num; // 一致しない => 無効
                    }
                }
            }
        }
        return $this;
    }
}
