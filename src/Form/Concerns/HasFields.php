<?php

namespace Encore\Admin\Form\Concerns;

use Encore\Admin\Form\Field;
use Illuminate\Support\Arr;

/**
 * @method Field\Text           text($column, $label = '')
 * @method Field\Checkbox       checkbox($column, $label = '')
 * @method Field\CheckboxButton checkboxButton($column, $label = '')
 * @method Field\CheckboxCard   checkboxCard($column, $label = '')
 * @method Field\Radio          radio($column, $label = '')
 * @method Field\RadioButton    radioButton($column, $label = '')
 * @method Field\RadioCard      radioCard($column, $label = '')
 * @method Field\Select         select($column, $label = '')
 * @method Field\MultipleSelect multipleSelect($column, $label = '')
 * @method Field\Textarea       textarea($column, $label = '')
 * @method Field\Hidden         hidden($column, $label = '')
 * @method Field\Id             id($column, $label = '')
 * @method Field\Ip             ip($column, $label = '')
 * @method Field\Url            url($column, $label = '')
 * @method Field\Color          color($column, $label = '')
 * @method Field\Email          email($column, $label = '')
 * @method Field\Mobile         mobile($column, $label = '')
 * @method Field\Slider         slider($column, $label = '')
 * @method Field\File           file($column, $label = '')
 * @method Field\Image          image($column, $label = '')
 * @method Field\Date           date($column, $label = '')
 * @method Field\Datetime       datetime($column, $label = '')
 * @method Field\Time           time($column, $label = '')
 * @method Field\Year           year($column, $label = '')
 * @method Field\Month          month($column, $label = '')
 * @method Field\DateRange      dateRange($start, $end, $label = '')
 * @method Field\DateMultiple   DateMultiple($column, $label = '')
 * @method Field\DateTimeRange  datetimeRange($start, $end, $label = '')
 * @method Field\TimeRange      timeRange($start, $end, $label = '')
 * @method Field\Number         number($column, $label = '')
 * @method Field\Currency       currency($column, $label = '')
 * @method Field\SwitchField    switch($column, $label = '')
 * @method Field\Display        display($column, $label = '')
 * @method Field\Rate           rate($column, $label = '')
 * @method Field\Divider        divider($title = '')
 * @method Field\Password       password($column, $label = '')
 * @method Field\Decimal        decimal($column, $label = '')
 * @method Field\Html           html($html, $label = '')
 * @method Field\Tags           tags($column, $label = '')
 * @method Field\Icon           icon($column, $label = '')
 * @method Field\Embeds         embeds($column, $label = '', $callback)
 * @method Field\MultipleImage  multipleImage($column, $label = '')
 * @method Field\MultipleFile   multipleFile($column, $label = '')
 * @method Field\Captcha        captcha($column, $label = '')
 * @method Field\Listbox        listbox($column, $label = '')
 * @method Field\Table          table($column, $label, $builder)
 * @method Field\Timezone       timezone($column, $label = '')
 * @method Field\KeyValue       keyValue($column, $label = '')
 * @method Field\ListField      list($column, $label = '')
 * @method Field\HasMany        hasMany($relationName, $label = '', $callback)
 * @method Field\HasMany        morphMany($relationName, $label = '', $callback)
 * @method Field\BelongsTo      belongsTo($column, $selectable, $label = '')
 * @method Field\BelongsToMany  belongsToMany($column, $selectable, $label = '')
 */
trait HasFields
{
    /**
     * Available fields.
     *
     * @var array
     */
    public static $availableFields = [
        'button'            => Field\Button::class,
        'checkbox'          => Field\Checkbox::class,
        'checkboxButton'    => Field\CheckboxButton::class,
        'checkboxCard'      => Field\CheckboxCard::class,
        'color'             => Field\Color::class,
        'currency'          => Field\Currency::class,
        'date'              => Field\Date::class,
        'dateRange'         => Field\DateRange::class,
        'DateMultiple'      => Field\DateMultiple::class,
        'datetime'          => Field\Datetime::class,
        'dateTimeRange'     => Field\DatetimeRange::class,
        'datetimeRange'     => Field\DatetimeRange::class,
        'decimal'           => Field\Decimal::class,
        'display'           => Field\Display::class,
        'divider'           => Field\Divider::class,
        'embeds'            => Field\Embeds::class,
        'email'             => Field\Email::class,
        'file'              => Field\File::class,
        'hidden'            => Field\Hidden::class,
        'id'                => Field\Id::class,
        'image'             => Field\Image::class,
        'ip'                => Field\Ip::class,
        'mobile'            => Field\Mobile::class,
        'month'             => Field\Month::class,
        'multipleSelect'    => Field\MultipleSelect::class,
        'number'            => Field\Number::class,
        'password'          => Field\Password::class,
        'radio'             => Field\Radio::class,
        'radioButton'       => Field\RadioButton::class,
        'radioCard'         => Field\RadioCard::class,
        'rate'              => Field\Rate::class,
        'select'            => Field\Select::class,
        'slider'            => Field\Slider::class,
        'switch'            => Field\SwitchField::class,
        'text'              => Field\Text::class,
        'textarea'          => Field\Textarea::class,
        'time'              => Field\Time::class,
        'timeRange'         => Field\TimeRange::class,
        'url'               => Field\Url::class,
        'year'              => Field\Year::class,
        'html'              => Field\Html::class,
        'tags'              => Field\Tags::class,
        'icon'              => Field\Icon::class,
        'multipleFile'      => Field\MultipleFile::class,
        'multipleImage'     => Field\MultipleImage::class,
        'captcha'           => Field\Captcha::class,
        'listbox'           => Field\Listbox::class,
        'table'             => Field\Table::class,
        'timezone'          => Field\Timezone::class,
        'keyValue'          => Field\KeyValue::class,
        'list'              => Field\ListField::class,
        'hasMany'           => Field\HasMany::class,
        'morphMany'         => Field\HasMany::class,
        'belongsTo'         => Field\BelongsTo::class,
        'belongsToMany'     => Field\BelongsToMany::class,
    ];

    /**
     * Form field alias.
     *
     * @var array
     */
    public static $fieldAlias = [];

    /**
     * Register custom field.
     *
     * @param string $abstract
     * @param string $class
     *
     * @return void
     */
    public static function extend($abstract, $class)
    {
        static::$availableFields[$abstract] = $class;
    }

    /**
     * Set form field alias.
     *
     * @param string $field
     * @param string $alias
     *
     * @return void
     */
    public static function alias($field, $alias)
    {
        static::$fieldAlias[$alias] = $field;
    }

    /**
     * Remove registered field.
     *
     * @param array|string $abstract
     */
    public static function forget($abstract)
    {
        Arr::forget(static::$availableFields, $abstract);
    }

    /**
     * Find field class.
     *
     * @param string $method
     *
     * @return bool|mixed
     */
    public static function findFieldClass($method)
    {
        // If alias exists.
        if (isset(static::$fieldAlias[$method])) {
            $method = static::$fieldAlias[$method];
        }

        $class = Arr::get(static::$availableFields, $method);

        if (class_exists($class)) {
            return $class;
        }

        return false;
    }

    /**
     * Collect assets required by registered field.
     *
     * @return array
     */
    public static function collectFieldAssets(): array
    {
        if (!empty(static::$collectedAssets)) {
            return static::$collectedAssets;
        }

        $css = collect();
        $js = collect();

        foreach (static::$availableFields as $field) {
            if (!method_exists($field, 'getAssets')) {
                continue;
            }

            $assets = call_user_func([$field, 'getAssets']);

            $css->push(Arr::get($assets, 'css'));
            $js->push(Arr::get($assets, 'js'));
        }

        return static::$collectedAssets = [
            'css' => $css->flatten()->unique()->filter()->toArray(),
            'js'  => $js->flatten()->unique()->filter()->toArray(),
        ];
    }
}
