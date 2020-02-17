<?php

namespace tpext\builder\common;

use think\response\View as ViewShow;

class Content implements Renderable
{
    /**
     * Undocumented variable
     *
     * @var ViewShow
     */
    protected $content;

    protected $partial = false;

    public function render()
    {
        if ($this->partial) {
            return $this->content;
        }

        return $this->content->getContent();
    }

    /**
     * Undocumented function
     *
     * @param boolean $val
     * @return $this
     */
    public function partial($val = true)
    {
        $this->partial = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $template
     * @param array $vars
     * @param array $config
     * @return $this
     */
    public function fetch($template = '', $vars = [], $config = [])
    {
        $this->content = new ViewShow($template);

        $this->content->assign($vars)->config($config);
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $template
     * @param array $vars
     * @param array $config
     * @return $this
     */
    public function display($content = '', $vars = [], $config = [])
    {
        $this->content = new ViewShow($content);

        $this->content->assign($vars)->config($config)->isContent(true);
        return $this;
    }

    public function beforRender()
    {
        return $this;
    }
}