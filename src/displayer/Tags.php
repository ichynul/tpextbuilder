<?php

namespace tpext\builder\displayer;

class Tags extends Field
{
    protected $view = 'tags';

    protected $js = [
        '/assets/tpextbuilder/js/jquery-tags-input/jquery.tagsinput.min.js',
    ];

    protected $css = [
        '/assets/tpextbuilder/js/jquery-tags-input/jquery.tagsinput.min.css',
    ];

    protected $placeholder = '';

    protected $jsOptions = [
        'height' => '33px',
        'width' => '100%',
        'defaultText' => '',
        'removeWithBackspace' => true,
        'delimiter' => [','],
    ];

    /**
     * Undocumented function
     *
     * @param string $val
     * @return $this
     */
    public function placeholder($val)
    {
        $this->placeholder = $val;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param array $options
     * @return $this
     */
    public function jsOptions($options)
    {
        $this->jsOptions = array_merge($this->jsOptions, $options);
        return $this;
    }

    protected function tagsScript()
    {
        $inputId = $this->getId();

        $this->jsOptions['placeholder'] = $this->placeholder;

        unset($this->jsOptions['locale']);

        $configs = json_encode($this->jsOptions);

        $configs = substr($configs, 1, strlen($configs) - 2);

        $script = <<<EOT
        $('#{$inputId}').tagsInput({
			{$configs}
		});

EOT;
        $this->script[] = $script;

        return $script;
    }

    public function beforRender()
    {
        $this->tagsScript();

        return parent::beforRender();
    }

    public function render()
    {
        $vars = $this->commonVars();

        $vars = array_merge($vars, [
            'placeholder' => $this->placeholder,
        ]);

        $viewshow = $this->getViewInstance();

        return $viewshow->assign($vars)->getContent();
    }
}
