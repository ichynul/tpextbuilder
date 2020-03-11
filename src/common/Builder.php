<?php

namespace tpext\builder\common;

use think\facade\View;
use think\response\View as ViewShow;

class Builder implements Renderable
{
    private $view = '';

    protected $title = '';

    protected $desc = null;

    protected $csrf_token = '';

    protected static $minify = false;

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected $rows = [];

    protected $__row__ = null;

    protected $js = [];

    protected $css = [];

    protected $style = [];

    protected $script = [];

    protected static $instance = null;

    protected $notify = [];

    protected $layer;

    protected function __construct($title, $desc)
    {
        $this->title = $title;
        $this->desc = $desc;
    }

    /**
     * Undocumented function
     *
     * @param string $title
     * @param string $desc
     * @return $this
     */
    public static function getInstance($title = '', $desc = '')
    {
        if (static::$instance == null) {
            static::$instance = new static($title, $desc);
        } else {
            if ($title) {
                static::$instance->setTitle($title);
                static::$instance->setDesc($desc);
            }
            if ($desc) {
                static::$instance->setTitle($title);
                static::$instance->setDesc($desc);
            }
        }

        return static::$instance;
    }

    /**
     * Undocumented function
     *
     * @param string $val
     * @return $this
     */
    public function setTitle($val)
    {
        $this->title = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $val
     * @return $this
     */
    public function setDesc($val)
    {
        $this->desc = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getCsrfToken()
    {
        if (!$this->csrf_token) {
            $token = csrf_token();
            $this->csrf_token = $token;
            View::share(['__token__' => $token]);
        }

        return $this->csrf_token;
    }

    /**
     * Undocumented function
     *
     * @param array|string $val
     * @return $this
     */
    public function addJs($val)
    {
        if (!is_array($val)) {
            $val = [$val];
        }
        $this->js = array_merge($this->js, $val);
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param array|string $val
     * @return $this
     */
    public function addCss($val)
    {
        if (!is_array($val)) {
            $val = [$val];
        }
        $this->css = array_merge($this->css, $val);
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param array|string $val
     * @return $this
     */
    public function addScript($val)
    {
        if (!is_array($val)) {
            $val = [$val];
        }
        $this->script = array_merge($this->script, $val);
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param array $val
     * @return $this
     */
    public function addStyle($val)
    {
        if (!is_array($val)) {
            $val = [$val];
        }
        $this->style = array_merge($this->style, $val);
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getJs()
    {
        return $this->js;
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getScript()
    {
        return $this->script;
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getCss()
    {
        return $this->css;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * Undocumented function
     * lightyear.notify('修改成功，页面即将自动跳转~', 'success', 5000, 'mdi mdi-emoticon-happy', 'top', 'center');
     * @param string $msg
     * @param string $type
     * @param integer $delay
     * @param string $icon
     * @param string $from
     * @param string $align
     * @return $this
     */
    public function notify($msg, $type = 'info', $delay = 2000, $icon = '', $from = 'top', $align = 'center')
    {
        $this->notify = [$msg, $type, $delay, $icon, $from, $align];
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getNotify()
    {
        return $this->notify;
    }

    /**
     * Undocumented function
     *
     * @return Row
     */
    public function row()
    {
        $row = new Row();
        $this->rows[] = $row;
        $this->__row__ = $row;
        return $row;
    }

    /**
     * Undocumented function
     *
     * @param integer $size
     * @return Column
     */
    public function column($size = 12)
    {
        if (!$this->__row__) {
            $this->row();
        }

        return $this->__row__->column($size);
    }

    /**
     * 获取一个form
     *
     * @param integer col大小
     * @return Form
     */
    public function form($size = 12)
    {
        return $this->column($size)->form();
    }

    /**
     * 获取一个表格
     *
     * @param integer col大小
     * @return Table
     */
    public function table($size = 12)
    {
        return $this->column($size)->table();
    }

    /**
     * 获取一个工具栏
     *
     * @param integer col大小
     * @return Toolbar
     */
    public function toolbar($size = 12)
    {
        return $this->column($size)->toolbar();
    }

    /**
     * 获取一自定义内容
     *
     * @param integer col大小
     * @return Content
     */
    public function content($size = 12)
    {
        return $this->column($size)->content();
    }

    /**
     * 获取一tab内容
     *
     * @param integer col大小
     * @return Tab
     */
    public function tab($size = 12)
    {
        return $this->column($size)->tab();
    }

    /**
     * 获取layer
     *
     * @return Layer
     */
    public function layer()
    {
        if (!$this->layer) {
            $this->layer = new Layer;
        }

        return $this->layer;
    }

    public function commonJs()
    {
        return [
            '/assets/tpextbuilder/js/jquery-validate/jquery.validate.min.js',
            '/assets/tpextbuilder/js/jquery-validate/messages_zh.min.js',
            '/assets/tpextbuilder/js/layer/layer.js',
            '/assets/tpextbuilder/js/tpextbuilder.js',
        ];
    }

    public function commonCss()
    {
        return '/assets/tpextbuilder/css/tpextbuilder.css';
    }

    public function beforRender()
    {
        foreach ($this->rows as $row) {
            $row->beforRender();
        }

        $this->addJs($this->commonJs());
        $this->addCss($this->commonCss());
    }

    /**
     * Undocumented function
     *
     * @param boolean $val
     * @return void
     */
    public static function minify($val)
    {
        static::$minify = $val;
    }

    /**
     * Undocumented function
     *
     * @return boolean
     */
    public static function isMinify()
    {
        return static::$minify;
    }

    /**
     * Undocumented function
     *
     * @return mixed
     */
    public function render()
    {
        $this->beforRender();

        $this->view = Module::getInstance()->getRoot() . implode(DIRECTORY_SEPARATOR, ['src', 'view', 'content.html']);

        if (!empty($this->notify)) {

            $this->script[] = "lightyear.notify('{$this->notify[0]}', '{$this->notify[1]}', {$this->notify[2]}, '{$this->notify[3]}', '{$this->notify[4]}', '{$this->notify[5]}');";
        }

        if (static::$minify) {
            $this->js = [];
            $this->css = [];
            $using = Wapper::getUsing();

            foreach ($using as $field) {
                if (!$field->canMinify()) {
                    $this->addJs($field->getJs());
                }
            }
        }

        $vars = [
            'title' => $this->title ? $this->title : 'Page',
            'desc' => $this->desc,
            'rows' => $this->rows,
            'js' => array_unique($this->js),
            'css' => array_unique($this->css),
            'style' => implode('', array_unique($this->style)),
            'script' => implode('', array_unique($this->script)),
        ];

        $view = new ViewShow($this->view);

        $instance = Module::getInstance();

        $instance->setConfig(['page_title' => $this->desc, 'page_position' => $this->title]);

        View::share(['admin_page_title' => $this->desc, 'admin_page_position' => $this->title]);

        return $view->assign($vars);
    }
}
