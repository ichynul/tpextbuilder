<?php

namespace tpext\builder\common;

class Wrapper
{
    protected static $displayers = [];

    protected static $displayersMap = [
        'field' => \tpext\builder\displayer\Field::class,
        'text' => \tpext\builder\displayer\Text::class,
        'textarea' => \tpext\builder\displayer\Textarea::class,
        'html' => \tpext\builder\displayer\Html::class,
        'divider' => \tpext\builder\displayer\Divider::class,
        'raw' => \tpext\builder\displayer\Raw::class,
        'checkbox' => \tpext\builder\displayer\Checkbox::class,
        'radio' => \tpext\builder\displayer\Radio::class,
        'button' => \tpext\builder\displayer\Button::class,
        'select' => \tpext\builder\displayer\Select::class,
        'multipleSelect' => \tpext\builder\displayer\MultipleSelect::class,
        'dualListbox' => \tpext\builder\displayer\DualListbox::class,
        'hidden' => \tpext\builder\displayer\Hidden::class,
        'switchBtn' => \tpext\builder\displayer\SwitchBtn::class,
        'tags' => \tpext\builder\displayer\Tags::class,
        'datetime' => \tpext\builder\displayer\DateTime::class,
        'date' => \tpext\builder\displayer\Date::class,
        'time' => \tpext\builder\displayer\Time::class,
        'datetimeRange' => \tpext\builder\displayer\DateTimeRange::class,
        'dateRange' => \tpext\builder\displayer\DateRange::class,
        'timeRange' => \tpext\builder\displayer\TimeRange::class,
        'color' => \tpext\builder\displayer\Color::class,
        'number' => \tpext\builder\displayer\Number::class,
        'icon' => \tpext\builder\displayer\Icon::class,
        'wangEditor' => \tpext\builder\displayer\WangEditor::class,
        'tinymce' => \tpext\builder\displayer\Tinymce::class,
        'ueditor' => \tpext\builder\displayer\UEditor::class,
        'ckeditor' => \tpext\builder\displayer\CKEditor::class,
        'mdeditor' => \tpext\builder\displayer\MDEditor::class,
        'mdreader' => \tpext\builder\displayer\MDReader::class,
        'editor' => \tpext\builder\displayer\WangEditor::class,
        'rate' => \tpext\builder\displayer\Rate::class,
        'month' => \tpext\builder\displayer\Month::class,
        'year' => \tpext\builder\displayer\Year::class,
        'multipleFile' => \tpext\builder\displayer\MultipleFile::class,
        'file' => \tpext\builder\displayer\File::class,
        'files' => \tpext\builder\displayer\MultipleFile::class,
        'multipleImage' => \tpext\builder\displayer\MultipleImage::class,
        'image' => \tpext\builder\displayer\Image::class,
        'images' => \tpext\builder\displayer\MultipleImage::class,
        'rangeSlider' => \tpext\builder\displayer\RangeSlider::class,
        'match' => \tpext\builder\displayer\Matche::class,
        'matches' => \tpext\builder\displayer\Matches::class,
        'show' => \tpext\builder\displayer\Show::class,
        'password' => \tpext\builder\displayer\Password::class,
        'fields' => \tpext\builder\displayer\Fields::class,
        'map' => \tpext\builder\displayer\Map::class,
        'items' => \tpext\builder\displayer\Items::class,
    ];

    protected static $defaultFieldClass = [
        \tpext\builder\displayer\Button::class => 'btn-default',
        \tpext\builder\displayer\Checkbox::class => 'checkbox-default',
        \tpext\builder\displayer\Radio::class => 'radio-default',
        \tpext\builder\displayer\SwitchBtn::class => 'switch-outline switch-primary',
    ];

    protected static $using = [];

    /**
     * Undocumented function
     *
     * @param string $class
     * @return void
     */
    public static function addUsing($class)
    {
        static::$using[] = $class;
    }

    /**
     * Undocumented function
     *
     * @param string $name
     * @return boolean
     */
    public static function isDisplayer($name)
    {
        if (empty(static::$displayers)) {
            static::$displayers = array_keys(static::$displayersMap);
        }

        return in_array($name, static::$displayers);
    }

    /**
     * Undocumented function
     *
     * @param string $type
     * @return string
     */
    public static function hasDefaultFieldClass($type)
    {
        if (isset(static::$defaultFieldClass[$type])) {
            return static::$defaultFieldClass[$type];
        }

        return '';
    }

    /**
     * Undocumented function
     *
     * @param array $pair
     * @return void
     */
    public static function extend($pair)
    {
        static::$displayersMap = array_merge(static::$displayersMap, $pair);
    }

    /**
     * Undocumented function
     * @deprecated 1.9.0010,3.3.2
     * @return array
     */
    public static function getDisplayerMap()
    {
        return static::$displayersMap;
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public static function getDisplayersMap()
    {
        return static::$displayersMap;
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public static function getUsing()
    {
        return static::$using;
    }

    /**
     * Undocumented function
     *
     * @param array $pair
     * @return void
     */
    public static function setDefaultFieldClass($pair)
    {
        static::$defaultFieldClass = array_merge(static::$defaultFieldClass, $pair);
    }
}
