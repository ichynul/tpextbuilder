<?php

namespace tpext\builder\displayer;

class DateTimeRange extends Text
{
    protected $js = [
        '/assets/tpextbuilder/js/moment/moment.min.js',
        '/assets/tpextbuilder/js/moment/locale/zh-cn.js',
        '/assets/tpextbuilder/js/bootstrap-daterangepicker/daterangepicker.min.js',
    ];

    protected $css = [
        '/assets/tpextbuilder/js/bootstrap-daterangepicker/daterangepicker.css',
    ];

    protected $format = 'YYYY-MM-DD HH:mm:ss';

    protected $befor = '<span class="input-group-addon"><i class="mdi mdi-calendar-multiple"></i></span>';

    protected $timePicker = true;

    protected $jsOptions = [
        'opens' => 'right',
        'showDropdowns' => true,
        'timePicker24Hour' => true, //设置小时为24小时制
        'locale' => [
            'separator' => ' ~ ',
        ],
    ];

    /**
     * Undocumented function
     *
     * @param array $options
     * @return void
     */
    public function jsOptions($options)
    {
        $this->jsOptions = array_merge($this->jsOptions, $options);
    }

    protected function dateTimeRangeScript()
    {
        $script = '';
        $inputId = $this->getId();

        $str = preg_replace('/\W/', '', $this->name);

        $this->jsOptions['timePicker'] = $this->timePicker;

        $this->jsOptions['locale'] = array_merge($this->jsOptions['locale'], ['format' => $this->format]);

        $configs = json_encode($this->jsOptions);

        $configs = substr($configs, 1, strlen($configs) - 2);

        $script = <<<EOT

        $('#{$inputId}').daterangepicker({
            {$configs}
        });

EOT;
        $this->script[] = $script;

        return $script;
    }

    /**
     * Undocumented function
     * YYYY-MM-DD HH:mm:ss
     * @param string $val
     * @return $this
     */
    public function format($val)
    {
        $this->format = $val;
        return $this;
    }

    public function beforRender()
    {
        $this->dateTimeRangeScript();

        return parent::beforRender();
    }
}
