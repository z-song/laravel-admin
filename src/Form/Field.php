<?php

namespace Encore\Admin\Form;

use Closure;
use Encore\Admin\Admin;
use Encore\Admin\Form;
use Encore\Admin\Widgets\Form as WidgetForm;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;

/**
 * Class Field.
 */
class Field implements Renderable
{
    use Macroable;
    use Form\Concerns\ValidatesField;

    const FILE_DELETE_FLAG = '_file_del_';
    const FILE_SORT_FLAG = '_file_sort_';

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
     * Data of all original columns of value.
     *
     * @var mixed
     */
    protected $data;

    /**
     * Field original value.
     *
     * @var mixed
     */
    protected $original;

    /**
     * Field default value.
     *
     * @var mixed
     */
    protected $default;

    /**
     * Element label.
     *
     * @var string
     */
    protected $label = '';

    /**
     * Column name.
     *
     * @var string|array
     */
    protected $column = '';

    /**
     * Form element name.
     *
     * @var string
     */
    protected $elementName = [];

    /**
     * Form element classes.
     *
     * @var array
     */
    protected $elementClass = [];

    /**
     * Variables of elements.
     *
     * @var array
     */
    protected $variables = [];

    /**
     * Options for specify elements.
     *
     * @var array
     */
    protected $options = [];

    /**
     * Checked for specify elements.
     *
     * @var array
     */
    protected $checked = [];

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
     * Parent form.
     *
     * @var Form
     */
    protected $form = null;

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
     * Placeholder for this field.
     *
     * @var string|array
     */
    protected $placeholder;

    /**
     * Width for label and field.
     *
     * @var array
     */
    protected $width = [
        'label' => 2,
        'field' => 8,
    ];

    /**
     * If the form horizontal layout.
     *
     * @var bool
     */
    protected $horizontal = true;

    /**
     * column data format.
     *
     * @var \Closure
     */
    protected $customFormat = null;

    /**
     * @var bool
     */
    protected $display = true;

    /**
     * @var array
     */
    protected $labelClass = [];

    /**
     * @var array
     */
    protected $groupClass = [];

    /**
     * @var \Closure
     */
    protected $callback;

    /**
     * @var bool
     */
    public $isJsonType = false;

    /**
     * @var bool
     */
    protected $nested = false;

    /**
     * Field constructor.
     *
     * @param       $column
     * @param array $arguments
     */
    public function __construct($column = '', $arguments = [])
    {
        $this->column = $this->formatColumn($column);
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
     * Format the field column name.
     *
     * @param string $column
     *
     * @return mixed|string
     */
    protected function formatColumn($column = '')
    {
        if (Str::contains($column, '->')) {
            $this->isJsonType = true;

            $column = str_replace('->', '.', $column);
        }

        return $column;
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
    protected function formatLabel($arguments = []): string
    {
        $column = is_array($this->column) ? current($this->column) : $this->column;

        $label = $arguments[0] ?? ucfirst($column);

        return str_replace(['.', '_', '->'], ' ', $label);
    }

    /**
     * Format the name of the field.
     *
     * @param string $column
     *
     * @return array|mixed|string
     */
    protected function formatName($column, $prefix = '')
    {
        if (is_string($column)) {
            if (Str::contains($column, '->')) {
                $name = explode('->', $column);
            } else {
                $name = explode('.', $column);
            }

            if (count($name) === 1) {
                return $prefix.$name[0];
            }

            $html = array_shift($name);
            foreach ($name as $piece) {
                $html .= "[$piece]";
            }

            return $prefix.$html;
        }

        if (is_array($this->column)) {
            $names = [];
            foreach ($this->column as $key => $name) {
                $names[$key] = $this->formatName($name, $prefix);
            }

            return $names;
        }

        return $prefix.'';
    }

    protected function formatClassName($column, $prefix = '')
    {
        if (is_string($column)) {
            if (Str::contains($column, '->')) {
                $name = explode('->', $column);
            } else {
                $name = explode('.', $column);
            }

            return $prefix.join('-', $name);
        }

        if (is_array($this->column)) {
            $names = [];
            foreach ($this->column as $key => $name) {
                $names[$key] = $this->formatClassName($name, $prefix);
            }

            return $names;
        }

        return $prefix.'';
    }

    /**
     * Set form element name.
     *
     * @param string $name
     *
     * @return $this
     *
     * @author Edwin Hui
     */
    public function setElementName($name): self
    {
        $this->elementName = $name;

        return $this;
    }

    /**
     * Fill data to the field.
     *
     * @param array $data
     *
     * @return void
     */
    public function fill($data)
    {
        $this->data = $data;

        if (is_array($this->column)) {
            foreach ($this->column as $key => $column) {
                $this->value[$key] = Arr::get($data, $column);
            }

            return;
        }

        $this->value = Arr::get($data, $this->column);

        $this->formatValue();
    }

    /**
     * Format value by passing custom formater.
     */
    protected function formatValue()
    {
        if (isset($this->customFormat) && $this->customFormat instanceof \Closure) {
            $this->value = call_user_func($this->customFormat, $this->value);
        }
    }

    /**
     * custom format form column data when edit.
     *
     * @param \Closure $call
     *
     * @return $this
     */
    public function customFormat(\Closure $call): self
    {
        $this->customFormat = $call;

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
                $this->original[$key] = Arr::get($data, $column);
            }

            return;
        }

        $this->original = Arr::get($data, $this->column);
    }

    /**`
     * @param Form $form
     *
     * @return $this
     */
    public function setForm($form = null)
    {
        $this->form = $form;

        return $this;
    }

    /**
     * Set Widget/Form as field parent.
     *
     * @param WidgetForm $form
     *
     * @return $this
     */
    public function setWidgetForm(WidgetForm $form)
    {
        $this->form = $form;

        return $this;
    }

    /**
     * Set width for field and label.
     *
     * @param int $field
     * @param int $label
     *
     * @return $this
     */
    public function setWidth($field = 8, $label = 2): self
    {
        $this->width = [
            'label' => $label,
            'field' => $field,
        ];

        return $this;
    }

    /**
     * Set the field options.
     *
     * @param array $options
     *
     * @return $this
     */
    public function options($options = [])
    {
        if ($options instanceof Arrayable) {
            $options = $options->toArray();
        }

        $this->options = array_merge($this->options, $options);

        return $this;
    }

    /**
     * Set the field option checked.
     *
     * @param array $checked
     *
     * @return $this
     */
    public function checked($checked = [])
    {
        if ($checked instanceof Arrayable) {
            $checked = $checked->toArray();
        }

        $this->checked = array_merge($this->checked, $checked);

        return $this;
    }

    /**
     * Set or get value of the field.
     *
     * @param null $value
     *
     * @return mixed
     */
    public function value($value = null)
    {
        if ($value === null) {
            return $this->value ?? $this->getDefault();
        }

        $this->value = $value;

        return $this;
    }

    /**
     * Set or get data.
     *
     * @param array $data
     *
     * @return mixed
     */
    public function data(array $data = null)
    {
        if ($data === null) {
            return $this->data;
        }

        $this->data = $data;

        return $this;
    }

    /**
     * Set default value for field.
     *
     * @param $default
     *
     * @return $this
     */
    public function default($default)
    {
        $this->default = $default;

        return $this;
    }

    /**
     * Get default value.
     *
     * @return mixed
     */
    public function getDefault()
    {
        if ($this->default instanceof \Closure) {
            return call_user_func($this->default, $this->form);
        }

        return $this->default;
    }

    /**
     * Set help block for current field.
     *
     * @param string $text
     * @param string $icon
     *
     * @return $this
     */
    public function help($text = '', $icon = 'fa-info-circle'): self
    {
        $this->help = compact('text', 'icon');

        return $this;
    }

    /**
     * Get column of the field.
     *
     * @return string|array
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
    public function label(): string
    {
        return $this->label;
    }

    /**
     * Get original value of the field.
     *
     * @return mixed
     */
    public function original()
    {
        return $this->original;
    }

    /**
     * Add html attributes to elements.
     *
     * @param array|string $attribute
     * @param mixed        $value
     *
     * @return $this
     */
    public function attribute($attribute, $value = null): self
    {
        if (is_array($attribute)) {
            $this->attributes = array_merge($this->attributes, $attribute);
        } else {
            $this->attributes[$attribute] = (string) $value;
        }

        return $this;
    }

    /**
     * Remove html attributes from elements.
     *
     * @param array|string $attribute
     *
     * @return $this
     */
    public function removeAttribute($attribute): self
    {
        Arr::forget($this->attributes, $attribute);

        return $this;
    }

    /**
     * Set Field style.
     *
     * @param string $attr
     * @param string $value
     *
     * @return $this
     */
    public function style($attr, $value): self
    {
        return $this->attribute('style', "{$attr}: {$value}");
    }

    /**
     * Set Field width.
     *
     * @param string $width
     *
     * @return $this
     */
    public function width($width): self
    {
        return $this->style('width', $width);
    }

    /**
     * Specifies a regular expression against which to validate the value of the input.
     *
     * @param string $regexp
     *
     * @return $this
     */
    public function pattern($regexp): self
    {
        return $this->attribute('pattern', $regexp);
    }

    /**
     * set the input filed required.
     *
     * @param bool $isLabelAsterisked
     *
     * @return $this
     */
    public function required($isLabelAsterisked = true): self
    {
        if ($isLabelAsterisked) {
            $this->setLabelClass(['asterisk']);
        }

        return $this->attribute('required', true);
    }

    /**
     * Set the field automatically get focus.
     *
     * @return $this
     */
    public function autofocus(): self
    {
        return $this->attribute('autofocus', true);
    }

    /**
     * Set the field as readonly mode.
     *
     * @return $this
     */
    public function readonly()
    {
        return $this->attribute('readonly', true);
    }

    /**
     * Set field as disabled.
     *
     * @return $this
     */
    public function disable()
    {
        return $this->attribute('disabled', true);
    }

    /**
     * Set field placeholder.
     *
     * @param string $placeholder
     *
     * @return $this
     */
    public function placeholder($placeholder = ''): self
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    /**
     * Get placeholder.
     *
     * @return mixed
     */
    public function getPlaceholder()
    {
        return $this->placeholder ?: trans('admin.input').' '.$this->label;
    }

    /**
     * Add a divider after this field.
     *
     * @return $this
     */
    public function divider()
    {
        $this->form->divider();

        return $this;
    }

    /**
     * Prepare for a field value before update or insert.
     *
     * @param $value
     *
     * @return mixed
     */
    public function prepare($value)
    {
        return $value;
    }

    /**
     * Format the field attributes.
     *
     * @return string
     */
    protected function formatAttributes(): string
    {
        return admin_attrs($this->attributes);
    }

    /**
     * @return $this
     */
    public function disableHorizontal(): self
    {
        $this->horizontal = false;

        return $this;
    }

    /**
     * Mark this field as in-nested form.
     */
    public function setNested()
    {
        $this->nested = true;
    }

    /**
     * @return array
     */
    public function getViewElementClasses(): array
    {
        if ($this->horizontal) {
            return [
                'label'      => "col-{$this->width['label']} {$this->getLabelClass()} text-right pr-3",
                'field'      => "col-{$this->width['field']} field-control",
                'form-group' => $this->getGroupClass(true),
            ];
        }

        return ['label' => $this->getLabelClass(), 'field' => '', 'form-group' => ''];
    }

    /**
     * Set form element class.
     *
     * @param string|array $class
     *
     * @return $this
     */
    public function setElementClass($class): self
    {
        $this->elementClass = array_merge($this->elementClass, (array) $class);

        return $this;
    }

    /**
     * Get element class.
     *
     * @return array
     */
    public function getElementClass(): array
    {
        if (!$this->elementClass) {
            $name = $this->elementName ?: $this->formatClassName($this->column, 'field-');

            $this->elementClass = (array) str_replace(['[', ']'], '_', $name);
        }

        return $this->elementClass;
    }

    /**
     * Get element class string.
     *
     * @return mixed
     */
    public function getElementClassString()
    {
        $elementClass = $this->getElementClass();

        if (Arr::isAssoc($elementClass)) {
            $classes = [];

            foreach ($elementClass as $index => $class) {
                $classes[$index] = is_array($class) ? implode(' ', $class) : $class;
            }

            return $classes;
        }

        return implode(' ', $elementClass);
    }

    /**
     * Get element class selector.
     *
     * @return string|array
     */
    public function getElementClassSelector()
    {
        $elementClass = $this->getElementClass();

        if (Arr::isAssoc($elementClass)) {
            $classes = [];

            foreach ($elementClass as $index => $class) {
                $classes[$index] = '.'.(is_array($class) ? implode('.', $class) : $class);
            }

            return $classes;
        }

        return '.'.implode('.', $elementClass);
    }

    /**
     * Add the element class.
     *
     * @param $class
     *
     * @return $this
     */
    public function addElementClass($class): self
    {
        if (is_array($class) || is_string($class)) {
            $this->elementClass = array_unique(array_merge($this->elementClass, (array) $class));
        }

        return $this;
    }

    /**
     * Remove element class.
     *
     * @param $class
     *
     * @return $this
     */
    public function removeElementClass($class): self
    {
        $delClass = [];

        if (is_string($class) || is_array($class)) {
            $delClass = (array) $class;
        }

        foreach ($delClass as $del) {
            if (($key = array_search($del, $this->elementClass, true)) !== false) {
                unset($this->elementClass[$key]);
            }
        }

        return $this;
    }

    /**
     * Set form group class.
     *
     * @param string|array $class
     *
     * @return $this
     */
    public function setGroupClass($class): self
    {
        if (is_array($class)) {
            $this->groupClass = array_merge($this->groupClass, $class);
        } else {
            $this->groupClass[] = $class;
        }

        return $this;
    }

    /**
     * Get element class.
     *
     * @param bool $default
     *
     * @return string
     */
    protected function getGroupClass($default = false): string
    {
        return ($default ? 'form-group row' : '').implode(' ', array_filter($this->groupClass));
    }

    /**
     * reset field className.
     *
     * @param string $className
     * @param string $resetClassName
     *
     * @return $this
     */
    public function resetElementClassName(string $className, string $resetClassName): self
    {
        if (($key = array_search($className, $this->getElementClass())) !== false) {
            $this->elementClass[$key] = $resetClassName;
        }

        return $this;
    }

    /**
     * Add variables to field view.
     *
     * @param array $variables
     *
     * @return $this
     */
    public function addVariables($variables = []): self
    {
        if (func_num_args() == 2) {
            $variables = [func_get_arg(0) => func_get_arg(1)];
        }

        $this->variables = array_merge($this->variables, $variables);

        return $this;
    }

    /**
     * @return string
     */
    public function getLabelClass(): string
    {
        return implode(' ', $this->labelClass);
    }

    /**
     * @param array $labelClass
     *
     * @return self
     */
    public function setLabelClass(array $labelClass): self
    {
        $this->labelClass = array_merge($this->labelClass, $labelClass);

        return $this;
    }

    /**
     * Get the view variables of this field.
     *
     * @return array
     */
    public function variables(): array
    {
        $viewClass = $this->getViewElementClasses();

        return array_merge($this->variables, [
            'id'          => $this->id,
            'name'        => $this->elementName ?: $this->formatName($this->column),
            'help'        => $this->help,
            'class'       => $this->getElementClassString(),
            'value'       => $this->value(),
            'label'       => $this->label,
            'viewClass'   => $viewClass,
            'column'      => $this->column,
            'selector'    => $this->getElementClassSelector(),
            'errorKey'    => $this->getErrorKey(),
            'attributes'  => $this->formatAttributes(),
            'placeholder' => $this->getPlaceholder(),
            'nested'      => (int) $this->nested,
            'group_attrs' => [
                'class'      => $viewClass['form-group'],
                'data-field' => is_array($this->column) ? join(',', $this->column) : $this->column,
                'data-type'  => strtolower((new \ReflectionClass($this))->getShortName()),
            ],
        ]);
    }

    /**
     * Get view of this field.
     *
     * @return string
     */
    public function getView(): string
    {
        if (!empty($this->view)) {
            return $this->view;
        }

        $class = explode('\\', static::class);

        return 'admin::form.'.strtolower(end($class));
    }

    /**
     * Set view of current field.
     *
     * @param string $view
     *
     * @return string
     */
    public function setView($view): self
    {
        $this->view = $view;

        return $this;
    }

    /**
     * Set script of current field.
     *
     * @param string $script
     *
     * @return $this
     */
    public function setScript($script): self
    {
        $this->script = $script;

        return $this;
    }

    /**
     * To set this field should render or not.
     *
     * @param bool $display
     *
     * @return $this
     */
    public function setDisplay(bool $display): self
    {
        $this->display = $display;

        return $this;
    }

    /**
     * If this field should render.
     *
     * @return bool
     */
    protected function shouldRender(): bool
    {
        if (!$this->display) {
            return false;
        }

        return true;
    }

    /**
     * @param \Closure $callback
     *
     * @return \Encore\Admin\Form\Field
     */
    public function with(Closure $callback): self
    {
        $this->callback = $callback;

        return $this;
    }

    /**
     * Render this filed.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function render()
    {
        if (!$this->shouldRender()) {
            return '';
        }

        $this->addRequiredAttributeFromRules();

        if ($this->callback instanceof Closure) {
            $this->value = $this->callback->call($this->form->model(), $this->value, $this);
        }

        if ($this->script) {
            Admin::script($this->script);
        }

        return Admin::view($this->getView(), $this->variables());
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    protected function fieldRender(array $variables = [])
    {
        if (!empty($variables)) {
            $this->addVariables($variables);
        }

        return self::render();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render()->render();
    }
}
