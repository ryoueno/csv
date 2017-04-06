<?php

namespace Gojiro;

/**
 * CSVファイルのフィールドの設定を管理
 */
class Field
{
    /** @var 有効なフィールドかどうか */
    public $enabled = true;

    /** @var フィールドの番号、csvは順番が大切 */
    public $idx;

    /** @var フィールドの名前 */
    public $name;

    public function __construct(int $idx, string $name)
    {
        if (!ctype_alpha(str_replace("_", "", $name))) {
            throw new \InvalidArgumentException("Field name only accepts alphabet and underscore.");
        }
        $this->idx = $idx;
        $this->name = $name;
    }

    /**
     * フィールドを無効にする
     */
    public function disable()
    {
        $this->enabled = false;
    }

    /**
     * フィールドを有効にする
     */
    public function enable()
    {
        $this->enabled = true;
    }
}
