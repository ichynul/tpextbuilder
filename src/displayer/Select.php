<?php

namespace tpext\builder\displayer;

use tpext\builder\traits\HasOptions;
use tpext\builder\traits\HasWhen;

class Select extends Field
{
    use HasOptions;
    use HasWhen;

    protected $view = 'select';

    protected $js = [
        '/assets/tpextbuilder/js/select2/select2.min.js',
        '/assets/tpextbuilder/js/select2/i18n/zh-CN.js',
    ];

    protected $css = [
        '/assets/tpextbuilder/js/select2/select2.min.css',
    ];

    protected $attr = 'size="1"';

    protected $group = false;

    protected $select2 = true;

    protected $prevSelect = null;

    protected $withParams = [];

    protected $jsOptions = [
        'placeholder' => '',
        'allowClear' => true,
        'minimumInputLength' => 0,
        'language' => 'zh-CN',
    ];

    /**
     * Undocumented function
     *
     * @param boolean $use
     * @return $this
     */
    public function select2($use)
    {
        $this->select2 = $use;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $url
     * @param string $textField text|name
     * @param string $idField
     * @param integer $delay
     * @param boolean $loadmore
     * @return $this
     */
    public function dataUrl($url, $textField = '', $idField = '', $delay = 250, $loadmore = true)
    {
        $this->jsOptions['ajax'] = [
            'url' => $url,
            'id' => $idField,
            'text' => $textField,
            'delay' => $delay,
            'loadmore' => $loadmore,
        ];

        return $this;
    }

    /**
     * Undocumented function
     *
     * @return boolean
     */
    public function isAjax()
    {
        return isset($this->jsOptions['ajax']);
    }

    /**
     * Undocumented function
     *
     * @param string $val
     * @return $this
     */
    public function placeholder($val)
    {
        $this->jsOptions['placeholder'] = $val;
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

    /**
     * Undocumented function
     *
     * @return string
     */
    protected function select2Script()
    {
        $script = '';
        $selectId = $this->getId();

        if (empty($this->jsOptions['placeholder'])) {
            $this->jsOptions['placeholder'] = '请选择' . $this->getlabel();
        }

        if (isset($this->jsOptions['ajax'])) {
            $ajax = $this->jsOptions['ajax'];
            unset($this->jsOptions['ajax']);
            $url = $ajax['url'];
            $id = $ajax['id'] ?: '_';
            $text = $ajax['text'] ?: '_';
            $delay = !empty($ajax['delay']) ? $ajax['delay'] : 250;
            $loadmore = $ajax['loadmore'];

            $configs = json_encode($this->jsOptions);

            $configs = substr($configs, 1, strlen($configs) - 2);

            $prev_id = isset($this->jsOptions['prev_id']) ? $this->jsOptions['prev_id'] : '';

            $key = preg_replace('/\W/', '', $selectId);

            $withParams = empty($this->withParams) ? '[]' : json_encode($this->withParams);

            $script = <<<EOT

        var withParams{$key} = JSON.parse('{$withParams}');

        var init{$key} = function()
        {
            $('#{$selectId}').addClass('select2-use-ajax').select2({
            {$configs},
            ajax: {
                url: '{$url}',
                dataType: 'json',
                delay: {$delay},
                data: function (params) {
                    var prev_val = '{$prev_id}' ? $('#{$prev_id}').val() : '';
                    var data = {
                        q: params.term,
                        page: params.page || 1,
                        prev_val : prev_val,
                        ele_id : '{$selectId}',
                        prev_ele_id : '{$prev_id}',
                        idField : '{$id}' == '_' ? null : '{$id}',
                        textField : '{$text}' == '_' ? null : '{$text}'
                    };
                    if(withParams{$key}.length)
                    {
                        for(var i in withParams{$key})
                        {
                            data[withParams{$key}[i]] =　$(":input[name='" + withParams{$key}[i] + "']").val();
                        }
                    }
                    return data;
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    var list = data.data ? data.data : data;
                    return {
                        results: $.map(list, function (d) {
                                d.id = d.__id__ || d['{$id}'] || d.id;
                                d.text = d.__text__ || d['{$text}'] || d.text;
                                return d;
                                }),
                        pagination: {
                        more: {$loadmore} ? data.has_more : 0
                        }
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) {
                return markup;
            }
            });
        }

        var selected{$key} = $('#{$selectId}').data('selected');
        var readonly{$key} = $('#{$selectId}').attr('readonly') != undefined;

        if(selected{$key})
        {
            $.ajax({
                url: '{$url}',
                data: {selected : selected{$key}},
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    var list = (data.data ? data.data : data) || [];
                    var d = null;
                    if(readonly{$key})
                    {
                        $('#{$selectId}').replaceWith('<span style="line-height:33px;" id="{$selectId}-text"></span>');
                        $('#{$selectId}-text').parent('div').addClass('field-show');
                        var texts = [];
                        for(var i in list)
                        {
                            d = list[i];
                            texts.push(d.__text__ || d['{$text}'] || d.text);
                        }

                        $('#{$selectId}-text').text(texts.join('、'));
                    }
                    else
                    {
                        for(var i in list)
                        {
                            d = list[i];
                            $('#{$selectId}').append('<option selected value="' + (d.__id__ || d['{$id}'] || d.id) + '">' + (d.__text__ || d['{$text}'] || d.text) + '</option>');
                        }
                        init{$key}();
                    }
                },
                error:function(){
                    $('#{$selectId}').data('selected', '');
                    if(!readonly{$key})
                    {
                        init{$key}();
                    }
                }
            });
        }
        else
        {
            if(readonly{$key})
            {
                $('#{$selectId}').replaceWith('<span style="line-height:33px;" id="{$selectId}-text">-空-</span>');
                $('#{$selectId}-text').parent('div').addClass('field-show');
            }
            else
            {
                init{$key}();
            }
        }

EOT;
            $this->jsOptions['ajax'] = $ajax;
        } else {
            $configs = json_encode($this->jsOptions);

            $configs = substr($configs, 1, strlen($configs) - 2);

            $script = <<<EOT
            $('#{$selectId}').addClass('select2-no-ajax').select2({
                {$configs}
            });

EOT;
        }

        if (empty($prev)) {
            $this->script[] = $script;
        }

        return $script;
    }

    /*
    $form->select('province', '省份', 4)->dataUrl(url('province'))->withNext(
    $form->select('city', '城市', 4)->dataUrl(url('city'))->withNext(
    $form->select('area', '区域', 4)->dataUrl(url('area'))
    )
    );
     */

    /**
     * Undocumented function
     *
     * @param Select $nextSelect
     * @return $this
     */
    public function withNext($nextSelect)
    {
        $nextSelect->withPrev($this);
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param Select $prevSelect
     * @return $this
     */
    public function withPrev($prevSelect)
    {
        $this->prevSelect = $prevSelect;

        return $this;
    }

    /**
     * ajax 时，附带其他字段的值。
     * 与级联有所不同，级联时上级改变会清空下级，并重新加载。
     * 附带字段的值改变不会触发此控件的重新加载，只是在此控件重新加载的时候附加参数。
     * @param array|string $val
     * @return $this
     */
    public function withParams($val)
    {
        $this->withParams = is_array($val) ? $val : explode(',', $val);

        return $this;
    }

    protected function withPrevScript()
    {
        $selectId = $this->getId();

        $prevId = $this->prevSelect->getId();

        $script = <<<EOT
        $(document).on('change', "#{$prevId}", function () {
            $('#{$selectId}').find('option').remove();
            $('#{$selectId}').find('optgroup').remove();
            $('#{$selectId}').empty().append('<option value=""></option>').trigger('change');
        });

EOT;
        $this->script[] = $script;

        $this->jsOptions(['prev_id' => $prevId]);

        return $this;
    }

    protected function isGroup()
    {
        foreach ($this->options as $option) {

            if (isset($option['options']) && isset($option['label'])) {
                $this->group = true;
                break;
            }
        }

        return $this->group;
    }

    public function beforRender()
    {
        if ($this->select2) {
            if ($this->prevSelect) {
                $this->withPrevScript();
            }
            $this->select2Script();
        }

        $this->whenScript();

        return parent::beforRender();
    }

    public function render()
    {
        $vars = $this->commonVars();

        if (!($this->value === '' || $this->value === null)) {
            $this->checked = $this->value;
        } else {
            $this->checked = $this->default;
        }

        $this->isGroup();

        if (!$this->group && !isset($this->options[''])) {
            $this->options = ['' => $this->jsOptions['placeholder']] + $this->options;
        }

        $vars = array_merge($vars, [
            'checked' => '-' . $this->checked,
            'dataSelected' => $this->checked,
            'select2' => $this->select2,
            'group' => $this->group,
            'options' => $this->options,
        ]);

        $viewshow = $this->getViewInstance();

        return $viewshow->assign($vars)->getContent();
    }
}
