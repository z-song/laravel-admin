<?php

namespace Encore\Admin\Widgets;

use Closure;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form as BaseForm;
use Encore\Admin\Form\Field;
use Encore\Admin\Layout\Content;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Fluent;

/**
 * Class Form.
 *
 * @method Field\Text           text($name, $label = '')
 * @method Field\Password       password($name, $label = '')
 * @method Field\Checkbox       checkbox($name, $label = '')
 * @method Field\CheckboxButton checkboxButton($name, $label = '')
 * @method Field\CheckboxCard   checkboxCard($name, $label = '')
 * @method Field\Radio          radio($name, $label = '')
 * @method Field\RadioButton    radioButton($name, $label = '')
 * @method Field\RadioCard      radioCard($name, $label = '')
 * @method Field\Select         select($name, $label = '')
 * @method Field\MultipleSelect multipleSelect($name, $label = '')
 * @method Field\Textarea       textarea($name, $label = '')
 * @method Field\Hidden         hidden($name, $label = '')
 * @method Field\Id             id($name, $label = '')
 * @method Field\Ip             ip($name, $label = '')
 * @method Field\Url            url($name, $label = '')
 * @method Field\Email          email($name, $label = '')
 * @method Field\Mobile         mobile($name, $label = '')
 * @method Field\Slider         slider($name, $label = '')
 * @method Field\File           file($name, $label = '')
 * @method Field\Image          image($name, $label = '')
 * @method Field\Date           date($name, $label = '')
 * @method Field\Datetime       datetime($name, $label = '')
 * @method Field\Time           time($name, $label = '')
 * @method Field\Year           year($column, $label = '')
 * @method Field\Month          month($column, $label = '')
 * @method Field\DateRange      dateRange($start, $end, $label = '')
 * @method Field\DateTimeRange  dateTimeRange($start, $end, $label = '')
 * @method Field\TimeRange      timeRange($start, $end, $label = '')
 * @method Field\Number         number($name, $label = '')
 * @method Field\Currency       currency($name, $label = '')
 * @method Field\SwitchField    switch($name, $label = '')
 * @method Field\Display        display($name, $label = '')
 * @method Field\Rate           rate($name, $label = '')
 * @method Field\Divider        divider($title = '')
 * @method Field\Decimal        decimal($column, $label = '')
 * @method Field\Html           html($html)
 * @method Field\Tags           tags($column, $label = '')
 * @method Field\Icon           icon($column, $label = '')
 * @method Field\Captcha        captcha($column, $label = '')
 * @method Field\Listbox        listbox($column, $label = '')
 * @method Field\Table          table($column, $label, $builder)
 * @method Field\Timezone       timezone($column, $label = '')
 * @method Field\KeyValue       keyValue($column, $label = '')
 * @method Field\ListField      list($column, $label = '')
 * @method mixed                handle(Request $request)
 */
class Form implements Renderable
{
    use BaseForm\Concerns\HandleCascadeFields;
    use BaseForm\Concerns\ValidatesFields;
    use Form\HasResponse;

    /**
     * The title of form.
     *
     * @var string
     */
    public $title;

    /**
     * The description of form.
     *
     * @var string
     */
    public $description;

    /**
     * @var Field[]
     */
    protected $fields = [];

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * Available buttons.
     *
     * @var array
     */
    protected $buttons = ['reset', 'submit'];

    /**
     * Width for label and submit field.
     *
     * @var array
     */
    protected $width = [
        'label' => 2,
        'field' => 8,
    ];

    /**
     * @var string
     */
    public $confirm = '';

    /**
     * @var Form
     */
    protected $form;

    /**
     * Form constructor.
     *
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->fill($data);

        $this->initFormAttributes();

        $this->response = new Fluent();
    }

    /**
     * Get form title.
     *
     * @return mixed
     */
    public function title($title = '')
    {
        if ($title) {
            $this->title = $title;

            return $this;
        }

        return $this->title;
    }

    /**
     * Get form description.
     *
     * @return mixed
     */
    public function description()
    {
        return $this->description ?: ' ';
    }

    /**
     * @return array
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function confirm($message)
    {
        $this->confirm = $message;

        return $this;
    }

    /**
     * Fill data to form fields.
     *
     * @param array $data
     *
     * @return $this
     */
    public function fill($data = [])
    {
        if ($data instanceof Arrayable) {
            $data = $data->toArray();
        }

        if (!empty($data)) {
            $this->data = $data;
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function sanitize()
    {
        foreach (['_form_', '_token'] as $key) {
            request()->request->remove($key);
        }

        return $this;
    }

    /**
     * Initialize the form attributes.
     */
    protected function initFormAttributes()
    {
        $this->attributes = [
            'id'             => 'widget-form-'.uniqid(),
            'method'         => 'POST',
            'action'         => '',
            'class'          => 'form-horizontal',
            'accept-charset' => 'UTF-8',
        ];
    }

    /**
     * Add form attributes.
     *
     * @param string|array $attr
     * @param string       $value
     *
     * @return $this
     */
    public function attribute($attr, $value = '')
    {
        if (is_array($attr)) {
            foreach ($attr as $key => $value) {
                $this->attribute($key, $value);
            }
        } else {
            $this->attributes[$attr] = $value;
        }

        return $this;
    }

    /**
     * Format form attributes form array to html.
     *
     * @param array $attributes
     *
     * @return string
     */
    public function formatAttribute($attributes = [])
    {
        $attributes = $attributes ?: $this->attributes;

        if ($this->hasFile()) {
            $attributes['enctype'] = 'multipart/form-data';
        }

        return admin_attrs($attributes);
    }

    /**
     * Action uri of the form.
     *
     * @param string $action
     *
     * @return $this
     */
    public function action($action)
    {
        return $this->attribute('action', $action);
    }

    /**
     * Method of the form.
     *
     * @param string $method
     *
     * @return $this
     */
    public function method($method = 'POST')
    {
        if (strtolower($method) == 'put') {
            $this->hidden('_method')->default($method);

            return $this;
        }

        return $this->attribute('method', strtoupper($method));
    }

    /**
     * Disable reset button.
     *
     * @return $this
     */
    public function disableReset()
    {
        array_delete($this->buttons, 'reset');

        return $this;
    }

    /**
     * Disable submit button.
     *
     * @return $this
     */
    public function disableSubmit()
    {
        array_delete($this->buttons, 'submit');

        return $this;
    }

    /**
     * Set field and label width in current form.
     *
     * @param int $field
     * @param int $label
     *
     * @return $this
     */
    public function setWidth($field = 8, $label = 2)
    {
        collect($this->fields)->each(function ($item) use ($field, $label) {
            /* @var Field $field  */
            $item->setWidth($field, $label);
        });

        // set this width
        $this->width = compact('label', 'field');

        return $this;
    }

    /**
     * Determine if the form has field type.
     *
     * @param string $name
     *
     * @return bool
     */
    public function hasField($name)
    {
        return isset(BaseForm::$availableFields[$name]);
    }

    /**
     * Add a form field to form.
     *
     * @param Field $field
     *
     * @return $this
     */
    public function pushField(Field $field)
    {
        $field->setWidgetForm($this);

        array_push($this->fields, $field);

        return $this;
    }

    /**
     * Get all fields of form.
     *
     * @return \Illuminate\Support\Collection
     */
    public function fields()
    {
        return collect($this->fields);
    }

    /**
     * Get variables for render form.
     *
     * @return array
     */
    protected function getVariables()
    {
        $this->fields()->each->fill($this->data());

        return [
            'id'         => $this->attributes['id'],
            'fields'     => $this->fields,
            'attributes' => $this->formatAttribute(),
            'method'     => $this->attributes['method'],
            'buttons'    => $this->buttons,
            'width'      => $this->width,
            'confirm'    => $this->confirm,
            'title'      => $this->title(),
        ];
    }

    /**
     * Determine if form fields has files.
     *
     * @return bool
     */
    public function hasFile()
    {
        return $this->fields()->contains(function ($field) {
            return $field instanceof Field\File || $field instanceof Field\MultipleFile;
        });
    }

    /**
     * Validate this form fields.
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function validate(Request $request)
    {
        if (method_exists($this, 'form')) {
            $this->form();
        }

        return $this->validateErrorResponse($request->all());
    }

    /**
     * Add a fieldset to form.
     *
     * @param string  $title
     * @param Closure $setCallback
     *
     * @return Field\Fieldset
     */
    public function fieldset(string $title, Closure $setCallback)
    {
        $fieldset = new Field\Fieldset();

        $this->html($fieldset->start($title))->plain();

        $setCallback($this);

        $this->html($fieldset->end())->plain();

        return $fieldset;
    }

    protected function prepareForm()
    {
        if (method_exists($this, 'form')) {
            $this->form();
        }
    }

    protected function prepareHandle()
    {
        if (method_exists($this, 'handle')) {
            $this->method('POST');
            $this->action(admin_url('_handle_form_'));
            $this->hidden('_form_')->default(get_called_class());
        }
    }

    /**
     * Render the form.
     *
     * @return string
     */
    public function render()
    {
        $this->prepareForm();

        $this->prepareHandle();

        return Admin::view('admin::widgets.form', $this->getVariables());
    }

    /**
     * Generate a Field object and add to form builder if Field exists.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return Field|$this
     */
    public function __call($method, $arguments)
    {
        if (!$this->hasField($method)) {
            return $this;
        }

        $class = BaseForm::$availableFields[$method];

        $field = new $class(Arr::get($arguments, 0), array_slice($arguments, 1));

        return tap($field, function ($field) {
            $this->pushField($field);
        });
    }

    /**
     * @param Content $content
     *
     * @return Content
     */
    public function __invoke(Content $content)
    {
        return $content->title($this->title())
            ->description($this->description())
            ->body($this);
    }
}
