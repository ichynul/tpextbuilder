<?php

namespace tpext\builder\common;

use think\response\View as ViewShow;
use tpext\builder\inface\Renderable;
use tpext\builder\toolbar\Bar;
use tpext\builder\toolbar\Wapper;
use tpext\builder\traits\HasDom;

class Toolbar extends Wapper implements Renderable
{
    use HasDom;

    protected $view = '';

    protected $elms = [];

    protected $__elm__;

    protected $extKey = '';

    protected $elms_right = [];

    protected $elms_left = [];

    /**
     * Undocumented function
     *
     * @param string $val
     * @return $this
     */
    public function extKey($val)
    {
        $this->extKey = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return Bar
     */
    public function getCurrent()
    {
        return $this->__elm__;
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getElms()
    {
        return $this->elms;
    }

    /**
     * Undocumented function
     *
     * @return boolean
     */
    public function isEmpty()
    {
        return empty($this->elms);
    }

    /**
     * Undocumented function
     *
     * @return $this
     */
    public function beforRender()
    {
        $this->elms_left = $this->elms_right = [];

        foreach ($this->elms as $elm) {

            if ($this->extKey) {
                $elm->extKey($this->extKey);
            }

            if ($elm->isPullRight()) {
                $this->elms_right[] = $elm;
            } else {
                $this->elms_left[] = $elm;
            }

            $elm->beforRender();
        }

        return $this;
    }

    public function render()
    {
        $template = Module::getInstance()->getRoot() . implode(DIRECTORY_SEPARATOR, ['src', 'view', 'toolbar.html']);

        $viewshow = new ViewShow($template);

        $vars = [
            'elms' => $this->elms,
            'elms_left' => $this->elms_left,
            'elms_right' => $this->elms_right,
            'class' => $this->class,
            'attr' => $this->getAttrWithStyle(),
        ];

        return $viewshow->assign($vars)->getContent();
    }

    public function __toString()
    {
        return $this->render();
    }

    public function __call($name, $arguments)
    {
        $count = count($arguments);

        if ($count > 0 && static::isDisplayer($name)) {

            $class = static::$displayerMap[$name];

            $this->__elm__ = new $class($arguments[0], $count > 1 ? $arguments[1] : '');

            $this->__elm__->created();

            $this->elms[] = $this->__elm__;

            return $this->__elm__;
        }

        throw new \UnexpectedValueException('未知调用:' . $name);
    }
}
