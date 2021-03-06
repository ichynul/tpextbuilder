<?php

namespace tpext\builder\displayer;

use tpext\builder\traits\HasStorageDriver;

class Tinymce extends Field
{
    use HasStorageDriver;

    protected $view = 'tinymce';

    protected $minify = false;

    protected $js = [
        '/assets/buildertinymce/tinymce.min.js',
    ];

    protected $jsOptions = [
        'language' => 'zh_CN',
        'directionality' => 'ltl',
        'browser_spellcheck' => true,
        'contextmenu' => false,
        'height' => 600,
        'plugins' => [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste imagetools wordcount",
            "code",
        ],
        'toolbar' => "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | code",
    ];

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

    protected function editorScript()
    {
        if (!class_exists('\\tpext\\builder\\tinymce\\common\\Resource')) {
            $this->js = [];
            $this->script[] = 'layer.alert("未安装tinymce资源包！<pre>composer require ichynul/builder-tinymce</pre>");';
            return;
        }

        $inputId = $this->getId();

        if (!isset($this->jsOptions['images_upload_url']) || empty($this->jsOptions['images_upload_url'])) {

            $token = $this->getCsrfToken();

            $this->jsOptions['images_upload_url'] = url('/admin/upload/upfiles', ['utype' => 'tinymce', 'token' => $token, 'driver' => $this->getStorageDriver()]);
        }

        $this->jsOptions['selector'] = "#{$inputId}";

        $configs = json_encode($this->jsOptions);

        $script = <<<EOT

        tinymce.init({$configs});

EOT;
        $this->script[] = $script;

        return $script;
    }

    public function beforRender()
    {
        if (!$this->readonly) {
            $this->editorScript();
        }

        return parent::beforRender();
    }
}
