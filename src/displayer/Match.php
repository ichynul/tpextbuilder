<?php

namespace tpext\builder\displayer;

use tpext\builder\traits\HasOptions;

/**
 * Undocumented class
 * 弃用
 * 由于php8中`match`是关键字，使用Matche代替，后续将完全删除这个类
 * @deprecated 1.9.0004
 *
 */
class Match extends Raw
{
    use HasOptions;

    public function renderValue()
    {
        if (isset($this->options[$this->value])) {
            $this->value = $this->options[$this->value];
        } else if (isset($this->options['__default__'])) {
            $this->value = $this->options['__default__'];
        }

        return parent::renderValue();
    }
}
