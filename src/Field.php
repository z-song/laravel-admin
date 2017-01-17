<?php

namespace Encore\Admin;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Validator;

/**
 * Class Field.
 *
 * @method Field default($value) set field default value
 */
class Field
{
    /**
     * Element id.
     *
     * @var array|string
     */
    protected $id;

    /**
     * Element value.
     *
     * @var mixed
     */
    protected $value;

    /**
     * Field original value.
     *
     * @var mixed
     */
    protected $original;

    /**
     * Element label.
     *
     * @var string
     */
    protected $label = '';

    /**
     * Column name.
     *
     * @var string
     */
    protected $column = '';

    /**
     * Field element name.
     *
     * @var string
     */
    protected $elementName = '';

    /**
     * Field element name.
     *
     * @var string
     */
    protected $elementClass = '';

    /**
     * Variables of elements.
     *
     * @var array
     */
    protected $variables = [];

    /**
     * Validation rules.
     *
     * @var string
     */
    protected $rules = '';

    /**
     * Css required by this field.
     *
     * @var array
     */
    protected static $css = [];

    /**
     * Js required by this field.
     *
     * @var array
     */
    protected static $js = [];

    /**
     * Script for field.
     *
     * @var string
     */
    protected $script = '';

    /**
     * Element attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * owner, the top level with model()
     *
     * @var
     */
    protected $owner = null;

    /**
     * fields group
     *
     * @var Field
     */
    protected $group;

    /**
     * View for field to render.
     *
     * @var string
     */
    protected $view = '';

    /**
     * Help block.
     *
     * @var array
     */
    protected $help = [];

    /**
     * Key for errors.
     *
     * @var mixed
     */
    protected $errorKey;

    /**
     * Collected field assets.
     *
     * @var array
     */
    protected static $collectedAssets = [];

    /**
     * Available fields.
     *
     * @var array
     */
    public static $availableFields = [];


	/**
	 * Field constructor.
	 * @param $owner
	 * @param $column
	 * @param array $arguments
	 */
    public function __construct(/*&$owner,*/ $column, $arguments = [])
    {
//    	$this->owner = &$owner;
        $this->column = $column;
        $this->elementName = $this->formatName($this->column);
        $this->label = $this->formatLabel($arguments);
        $this->id = $this->formatId($column);

    }

    /**
     * Get assets required by this field.
     *
     * @return array
     */
    public static function getAssets()
    {
        return [
            'css' => static::$css,
            'js'  => static::$js,
        ];
    }

    /**
     * Format the field element id.
     *
     * @param string|array $column
     *
     * @return string|array
     */
    protected function formatId($column)
    {
        return str_replace('.', '_', $column);
    }

    /**
     * Format the label value.
     *
     * @param array $arguments
     *
     * @return string
     */
    protected function formatLabel($arguments = [])
    {
        $column = is_array($this->column) ? current($this->column) : $this->column;

        $label = isset($arguments[0]) ? $arguments[0] : ucfirst($column);

        return str_replace(['.', '_'], ' ', $label);
    }

    /**
     * Format the name of the field.
     *
     * @param string $column
     *
     * @return array|mixed|string
     */
    protected function formatName($column)
    {
        if (is_string($column)) {
            $name = explode('.', $column);

            if (count($name) == 1) {
                return $name[0];
            }

            $html = array_shift($name);
            foreach ($name as $piece) {
                $html .= "[$piece]";
            }

            return $html;
        }

        if (is_array($this->column)) {
            $names = [];
            foreach ($this->column as $key => $name) {
                $names[$key] = $this->formatName($name);
            }

            return $names;
        }

        return '';
    }

    /**
     * Set form element name.
     *
     * @param $name
     */
    public function setElementName($name)
    {
        $this->elementName = $name;
    }

    /**
     * Get form element name.
     *
     * @return string
     * author Edwin Hui
     */
    public function getElementName()
    {
        return $this->elementName;
    }


    public function setColumnName( $columnName )
    {
        $this->column = $columnName;
    }



    /**
     * @param $owner
     *
     * @return $this
     */
    public function setOwner(&$owner)
    {
        $this->owner = &$owner;

        return $this;
    }

    public function setGroup($group)
    {
        $this->group = $group;
    }

    public function getGroup()
    {
        return $this->group ? $this->group : $this;
    }

    /**
     * Fill data to the field.
     *
     * @param $data
     *
     * @return void
     */
    public function fill($data)
    {
        if (is_array($this->column)) {
            foreach ($this->column as $key => $column) {
                $this->value[$key] = array_get($data, $column);
            }

            return;
        }

        $this->value = array_get($data, $this->column);

	    return $this;
    }

    /**
     * Set original value to the field.
     *
     * @param array $data
     *
     * @return void
     */
    public function setOriginal($data)
    {
        if (is_array($this->column)) {
            foreach ($this->column as $key => $column) {
                $this->original[$key] = array_get($data, $column);
            }

            return;
        }

        $this->original = array_get($data, $this->column);
    }


    /**
     * Get or set rules.
     *
     * @param null $rules
     *
     * @return $this|array
     */
    public function rules($rules = null)
    {
        if (is_null($rules)) {
            return $this->rules;
        }

        $rules = array_filter(explode('|', "{$this->rules}|$rules"));

        $this->rules = implode('|', $rules);

        return $this;
    }

    /**
     * Get field validation rules.
     *
     * @return string
     */
    protected function getRules()
    {
        return $this->rules;
    }

    /**
     * Get key for error message.
     *
     * @return string
     */
    public function getErrorKey()
    {
        return $this->errorKey ?: $this->column;
    }

    /**
     * Set key for error message.
     *
     * @param string $key
     *
     * @return $this
     */
    public function setErrorKey($key)
    {
        $this->errorKey = $key;

        return $this;
    }



    /**
     * Set help block for current field.
     *
     * @param string $text
     * @param string $icon
     *
     * @return $this
     */
    public function help($text = '', $icon = 'fa-info-circle')
    {
        $this->help = compact('text', 'icon');

        return $this;
    }

    /**
     * Get/Set column of the field.
     *
     * @return string
     */
    public function column()
    {
        return $this->column;
    }

    /**
     * Get label of the field.
     *
     * @return string
     */
    public function label()
    {
        return $this->label;
    }

    /**
     * Get validator for this field.
     *
     * @param array $input
     *
     * @return bool|Validator
     */
    public function getValidator(array $input)
    {
        $rules = $attributes = [];

        if (!$fieldRules = $this->getRules()) {
            return false;
        }

        if (is_string($this->column)) {
            if (!array_has($input, $this->column)) {
                return false;
            }

            $rules[$this->column] = $fieldRules;
            $attributes[$this->column] = $this->label;
        }

        if (is_array($this->column)) {
            foreach ($this->column as $key => $column) {
                if (!array_key_exists($column, $input)) {
                    continue;
                }
                $input[$column.$key] = array_get($input, $column);
                $rules[$column.$key] = $fieldRules;
                $attributes[$column.$key] = $this->label."[$column]";
            }
        }

        return Validator::make($input, $rules, [], $attributes);
    }


    /**
     * Add html attributes to elements.
     *
     * @param array|string $attribute
     * @param mixed        $value
     *
     * @return $this
     */
    public function attribute($attribute, $value = null)
    {
        if (is_array($attribute)) {
            $this->attributes = array_merge($this->attributes, $attribute);
        } else {
            $this->attributes[$attribute] = (string) $value;
        }

        return $this;
    }


    /**
     * Format the field attributes.
     *
     * @return string
     */
    protected function formatAttributes()
    {
        $html = [];

        foreach ($this->attributes as $name => $value) {
            $html[] = "$name=\"$value\"";
        }

        return implode(' ', $html);
    }

    /**
     * Set form element class.
     *
     * @param string $class
     *
     * @return $this
     */
    public function setElementClass($class)
    {
        $this->elementClass = $class;

        return $this;
    }

    /**
     * Get element class.
     *
     * @return string
     */
    protected function getElementClass()
    {
        if (!$this->elementClass) {
            $name = $this->elementName ?: $this->formatName($this->column);

            $this->elementClass = str_replace(['[', ']'], '_', $name);
        }

        return $this->elementClass;
    }

    /**
     * Get the view variables of this field.
     *
     * @return array
     */
    protected function variables()
    {
        $this->variables['id'] = $this->id;
        $this->variables['name'] = $this->elementName ?: $this->formatName($this->column);
        $this->variables['label'] = $this->label;
        $this->variables['column'] = $this->column;
        $this->variables['attributes'] = $this->formatAttributes();
        $this->variables['help'] = $this->help;
        $this->variables['class'] = $this->getElementClass();
        $this->variables['errorKey'] = $this->getErrorKey();

        return $this->variables;
    }

    /**
     * set view.
     *
     * @param $view
     *
     * @return $this
     *               author Edwin Hui
     */
    public function setView($view)
    {
        $this->view = $view;

        return $this;
    }

    /**
     * Get view of this field.
     *
     * @return string
     */
    public function getView()
    {
        if (!empty($this->view)) {
            return $this->view;
        }

        $class = explode('\\', get_called_class());

        return 'admin::field.'.strtolower(end($class));
    }

    public function getScript()
    {
        return $this->script;
    }

    /**
     * Render this filed.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render()
    {
        Admin::script($this->script);

        return view($this->getView(), $this->variables());
    }

    /**
     * Register builtin fields.
     *
     * @return void
     */
    public static function registerBuiltinFields()
    {
        $map = [
            'button'            => \Encore\Admin\Field\DataField\Button::class,
            'checkbox'          => \Encore\Admin\Field\DataField\Checkbox::class,
            'color'             => \Encore\Admin\Field\DataField\Color::class,
            'currency'          => \Encore\Admin\Field\DataField\Currency::class,
            'date'              => \Encore\Admin\Field\DataField\Date::class,
            'dateRange'         => \Encore\Admin\Field\DataField\DateRange::class,
            'datetime'          => \Encore\Admin\Field\DataField\Datetime::class,
            'dateTimeRange'     => \Encore\Admin\Field\DataField\DatetimeRange::class,
            'decimal'           => \Encore\Admin\Field\DataField\Decimal::class,
            'display'           => \Encore\Admin\Field\DataField\Display::class,
            'divider'           => \Encore\Admin\Field\DataField\Divide::class,
            'divide'            => \Encore\Admin\Field\DataField\Divide::class,
            'editor'            => \Encore\Admin\Field\DataField\Editor::class,
            'email'             => \Encore\Admin\Field\DataField\Email::class,
            'embedsMany'        => \Encore\Admin\Field\DataField\EmbedsMany::class,
            'file'              => \Encore\Admin\Field\DataField\File::class,
//            'hasMany'           => \Encore\Admin\Field\DataField\HasMany::class,
            'hasMany'          => \Encore\Admin\Field\RelationField\HasMany::class,
            'hidden'            => \Encore\Admin\Field\DataField\Hidden::class,
            'id'                => \Encore\Admin\Field\DataField\Id::class,
            'image'             => \Encore\Admin\Field\DataField\Image::class,
            'ip'                => \Encore\Admin\Field\DataField\Ip::class,
            'map'               => \Encore\Admin\Field\DataField\Map::class,
            'mobile'            => \Encore\Admin\Field\DataField\Mobile::class,
            'month'             => \Encore\Admin\Field\DataField\Month::class,
            'multipleSelect'    => \Encore\Admin\Field\DataField\MultipleSelect::class,
            'number'            => \Encore\Admin\Field\DataField\Number::class,
            'password'          => \Encore\Admin\Field\DataField\Password::class,
            'radio'             => \Encore\Admin\Field\DataField\Radio::class,
            'rate'              => \Encore\Admin\Field\DataField\Rate::class,
            'select'            => \Encore\Admin\Field\DataField\Select::class,
            'slider'            => \Encore\Admin\Field\DataField\Slider::class,
            'switch'            => \Encore\Admin\Field\DataField\SwitchField::class,
            'text'              => \Encore\Admin\Field\DataField\Text::class,
            'textarea'          => \Encore\Admin\Field\DataField\Textarea::class,
            'time'              => \Encore\Admin\Field\DataField\Time::class,
            'timeRange'         => \Encore\Admin\Field\DataField\TimeRange::class,
            'url'               => \Encore\Admin\Field\DataField\Url::class,
            'year'              => \Encore\Admin\Field\DataField\Year::class,
            'html'              => \Encore\Admin\Field\DataField\Html::class,
            'tags'              => \Encore\Admin\Field\DataField\Tags::class,
            'icon'              => \Encore\Admin\Field\DataField\Icon::class,
        ];

        foreach ($map as $abstract => $class) {
            static::extend($abstract, $class);
        }
    }

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
     * Remove registered field.
     *
     * @param array|string $abstract
     */
    public static function forget($abstract)
    {
        array_forget(static::$availableFields, $abstract);
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
        $class = array_get(static::$availableFields, $method);

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
    public static function collectFieldAssets()
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

            $css->push(array_get($assets, 'css'));
            $js->push(array_get($assets, 'js'));
        }

        return static::$collectedAssets = [
            'css' => $css->flatten()->unique()->filter()->toArray(),
            'js'  => $js->flatten()->unique()->filter()->toArray(),
        ];
    }

}
