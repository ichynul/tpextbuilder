<?php

namespace tpext\builder\toolbar;

use tpext\builder\common\Builder;
use tpext\builder\common\Module;
use tpext\builder\inface\Renderable;
use tpext\builder\traits\HasDom;
use tpext\common\ExtLoader;

class Bar implements Renderable
{
    use HasDom;

    protected $view = '';

    protected $extKey = '';

    protected $icon = '';

    protected $href = 'javascript:;';

    protected $__href__ = '';

    protected $label = '';

    protected $script = [];

    protected $useLayer = true;

    protected $layerSize;

    protected $pullRight = false;

    public function __construct($name, $label = '')
    {
        $this->name = $name;
        $this->label = $label;
        $this->class = 'btn-default';
    }

    /**
     * Undocumented function
     *
     * @param string $fieldType
     * @return $this
     */
    public function created($barType = '')
    {
        $barType = $barType ? $barType : get_called_class();

        $barType = lcfirst($barType);

        $defaultClass = BWapper::hasDefaultFieldClass($barType);

        if (!empty($defaultClass)) {
            $this->class = $defaultClass;
        }

        ExtLoader::trigger('tpext_bar_created', $this);

        return $this;
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getId()
    {
        return 'bar-' . $this->name . preg_replace('/[^\w\-]/', '', $this->extKey);
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Undocumented function
     *
     * @param string $val
     * @return $this
     */
    public function label($val)
    {
        $this->label = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $val
     * @return $this
     */
    public function icon($val)
    {
        $this->icon = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $val
     * @return $this
     */
    public function href($val)
    {
        $this->href = (string)$val;

        if (!Builder::checkUrl($this->href)) {
            $this->addClass('hidden disabled');
        }

        return $this;
    }

    /**
     * Undocumented function
     *
     * @param boolean $val
     * @param array|string $size
     * @return $this
     */
    public function useLayer($val, $size = [])
    {
        $this->useLayer = $val;

        if (!empty($size)) {
            $this->layerSize = is_array($size) ? implode(',', $size) : $size;
        }

        return $this;
    }

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
     * @param boolean $val
     * @return $this
     */
    public function pullRight($val = true)
    {
        $this->pullRight = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return boolean
     */
    public function isPullRight()
    {
        return $this->pullRight;
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

    public function beforRender()
    {
        if (!empty($this->js)) {
            Builder::getInstance()->addJs($this->js);
        }

        if (!empty($this->css)) {
            Builder::getInstance()->addCss($this->css);
        }

        if (!empty($this->script)) {
            Builder::getInstance()->addScript($this->script);
        }

        ExtLoader::trigger('tpext_bar_befor_render', $this);

        return $this;
    }

    /**
     * Undocumented function
     *
     * @return mixed
     */
    public function render()
    {
        return '<!--empty bar-->';
    }

    protected function getViewInstance()
    {
        $template = Module::getInstance()->getRoot() . implode(DIRECTORY_SEPARATOR, ['src', 'view', 'toolbar', $this->view . '.html']);

        $viewshow = view($template);

        return $viewshow;
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function commonVars()
    {
        $this->useLayer = $this->useLayer && !empty($this->href) && !preg_match('/javascript:.*/i', $this->href) && !preg_match('/^#.*/i', $this->href);

        if (empty($this->layerSize)) {
            $config = Module::getInstance()->getConfig();
            $this->layerSize = $config['layer_size'];
        }

        if (strpos($this->attr, 'data-layer-size=') === false) {
            $this->addAttr('data-layer-size="' . $this->layerSize . '"');
        }

        $vars = [
            'id' => $this->getId(),
            'label' => $this->label,
            'name' => $this->getName(),
            'class' => ' ' . $this->class,
            'href' => empty($this->__href__) ? $this->href : $this->__href__,
            'icon' => $this->icon,
            'attr' => $this->getAttrWithStyle(),
            'useLayer' => $this->useLayer,
            'pullRight' => $this->pullRight
        ];

        return $vars;
    }
}
