<?php

namespace Encore\Admin\Actions\Interactor;

use Encore\Admin\Actions\RowAction;
use Encore\Admin\Admin;
use Encore\Admin\Form\Field;
use Encore\Admin\Form\Layout\Row;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;
use Symfony\Component\DomCrawler\Crawler;

class Form extends Interactor
{
    /**
     * @var array
     */
    protected $fields = [];

    /**
     * @var array
     */
    protected $rows = [];

    /**
     * @var string
     */
    protected $modalId;

    /**
     * @var string
     */
    protected $modalSize = '';

    /**
     * @var string
     */
    protected $confirm = '';

    /**
     * @param string $label
     *
     * @return array
     */
    protected function formatLabel($label)
    {
        return array_filter((array) $label);
    }

    /**
     * @param string $column
     * @param string $label
     *
     * @return Field\Text
     */
    public function text($column, $label = '')
    {
        $field = new Field\Text($column, $this->formatLabel($label));

        return $this->addField($field);
    }

    /**
     * @param $column
     * @param string   $label
     * @param \Closure $builder
     *
     * @return Field\Table
     */
    public function table($column, $label = '', $builder = null)
    {
        $field = new Field\Table($column, [$label, $builder]);

        return $this->addField($field);
    }

    /**
     * @param string $column
     * @param string $label
     *
     * @return Field\Text
     */
    public function email($column, $label = '')
    {
        $field = new Field\Email($column, $this->formatLabel($label));

        return $this->addField($field)->setView('admin::actions.form.text');
    }

    /**
     * @param string $column
     * @param string $label
     *
     * @return Field\Text
     */
    public function integer($column, $label = '')
    {
        return $this->text($column, $label)
            ->width('200px')
            ->inputmask(['alias' => 'integer']);
    }

    /**
     * @param string $column
     * @param string $label
     *
     * @return Field\Text
     */
    public function ip($column, $label = '')
    {
        return $this->text($column, $label)
            ->icon('fa-laptop')
            ->width('200px')
            ->inputmask(['alias' => 'ip']);
    }

    /**
     * @param string $column
     * @param string $label
     *
     * @return Field\Text
     */
    public function url($column, $label = '')
    {
        return $this->text($column, $label)
            ->prependText('<i class="fab fa-internet-explorer fa-fw"></i>')
            ->attribute('type', 'url')
            ->width('200px');
    }

    /**
     * @param string $column
     * @param string $label
     *
     * @return Field\Text
     */
    public function password($column, $label = '')
    {
        return $this->text($column, $label)
            ->icon('fa-eye-slash')
            ->attribute('type', 'password');
    }

    /**
     * @param string $column
     * @param string $label
     *
     * @return Field\Text
     */
    public function mobile($column, $label = '')
    {
        return $this->text($column, $label)
            ->icon('fa-phone')
            ->inputmask(['mask' => '99999999999'])
            ->width('100px');
    }

    /**
     * @param string $column
     * @param string $label
     *
     * @return Field\Textarea
     */
    public function textarea($column, $label = '')
    {
        $field = new Field\Textarea($column, $this->formatLabel($label));

        return $this->addField($field);
    }

    /**
     * @param string $column
     * @param string $label
     *
     * @return Field\Select
     */
    public function select($column, $label = '')
    {
        $field = new Field\Select($column, $this->formatLabel($label));

        return $this->addField($field);
    }

    /**
     * @param string $column
     * @param string $label
     *
     * @return Field\MultipleSelect
     */
    public function multipleSelect($column, $label = '')
    {
        $field = new Field\MultipleSelect($column, $this->formatLabel($label));

        return $this->addField($field);
    }

    /**
     * @param string $column
     * @param string $label
     *
     * @return Field\Checkbox
     */
    public function checkbox($column, $label = '')
    {
        admin_assets_require('icheck');

        $field = new Field\Checkbox($column, $this->formatLabel($label));

        return $this->addField($field);
    }

    /**
     * @param string $column
     * @param string $label
     *
     * @return Field\Radio
     */
    public function radio($column, $label = '')
    {
        admin_assets_require('icheck');

        $field = new Field\Radio($column, $this->formatLabel($label));

        return $this->addField($field);
    }

    /**
     * @param string $column
     * @param string $label
     *
     * @return Field\File
     */
    public function file($column, $label = '')
    {
        $field = new Field\File($column, $this->formatLabel($label));

        return $this->addField($field);
    }

    /**
     * @param string $column
     * @param string $label
     *
     * @return Field\MultipleFile
     */
    public function multipleFile($column, $label = '')
    {
        $field = new Field\MultipleFile($column, $this->formatLabel($label));

        return $this->addField($field);
    }

    /**
     * @param string $column
     * @param string $label
     *
     * @return Field\Image
     */
    public function image($column, $label = '')
    {
        $field = new Field\Image($column, $this->formatLabel($label));

        return $this->addField($field)->setView('admin::actions.form.file');
    }

    /**
     * @param string $column
     * @param string $label
     *
     * @return Field\MultipleImage
     */
    public function multipleImage($column, $label = '')
    {
        $field = new Field\MultipleImage($column, $this->formatLabel($label));

        return $this->addField($field)->setView('admin::actions.form.multiplefile');
    }

    /**
     * @param string $column
     * @param string $label
     *
     * @return Field\Date
     */
    public function date($column, $label = '')
    {
        $field = new Field\Date($column, $this->formatLabel($label));

        return $this->addField($field);
    }

    /**
     * @param string $column
     * @param string $label
     *
     * @return Field\Date
     */
    public function datetime($column, $label = '')
    {
        return $this->date($column, $label)->format('YYYY-MM-DD HH:mm:ss');
    }

    /**
     * @param string $column
     * @param string $label
     *
     * @return Field\Date
     */
    public function time($column, $label = '')
    {
        return $this->date($column, $label)->format('HH:mm:ss');
    }

    /**
     * @param string $column
     * @param string $label
     *
     * @return Field\Hidden
     */
    public function hidden($column, $label = '')
    {
        $field = new Field\Hidden($column, $this->formatLabel($label));

        return $this->addField($field);
    }

    /**
     * @param $message
     *
     * @return $this
     */
    public function confirm($message)
    {
        $this->confirm = $message;

        return $this;
    }

    /**
     * @return $this
     */
    public function modalLarge()
    {
        $this->modalSize = 'modal-lg';

        return $this;
    }

    /**
     * @return $this
     */
    public function modalSmall()
    {
        $this->modalSize = 'modal-sm';

        return $this;
    }

    /**
     * @param string $content
     * @param string $selector
     *
     * @return string
     */
    public function addElementAttr($content, $selector)
    {
        $crawler = new Crawler($content);

        $node = $crawler->filter($selector)->getNode(0);
        $node->setAttribute('modal', $this->getModalId());

        return $crawler->children()->html();
    }

    /**
     * @param Field $field
     *
     * @return Field
     */
    protected function addField(Field $field)
    {
        $elementClass = array_merge(['action'], $field->getElementClass());

        $field->addElementClass($elementClass);

        $field->setView($this->resolveView(get_class($field)));

        return $this->fields[] = $field;
    }

    /**
     * @param string $method
     * @param array $arguments
     * @return mixed
     */
    public function resolveField($method, $arguments)
    {
        return $this->{$method}(...$arguments);
    }

    /**
     * @param $method
     * @param $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        $field = $this->resolveField($method, $arguments);

        if (!$field instanceof Field) {
            return $field;
        }

        $this->row()->column()->addField($field);

        return $field;
    }

    /**
     * Add a row in form.
     *
     * @param Closure $callback
     *
     * @return \Encore\Admin\Form
     */
    public function row(\Closure $callback = null)
    {
        return $this->rows[] = new Row($this, $callback);
    }

    /**
     * @param Request $request
     *
     * @throws ValidationException
     * @throws \Exception
     *
     * @return void
     */
    public function validate(Request $request)
    {
        if ($this->action instanceof RowAction) {
            call_user_func([$this->action, 'form'], $this->action->getRow());
        } else {
            call_user_func([$this->action, 'form']);
        }

        $failedValidators = [];

        /** @var Field $field */
        foreach ($this->fields as $field) {
            if (!$validator = $field->getValidator($request->all())) {
                continue;
            }

            if (($validator instanceof Validator) && !$validator->passes()) {
                $failedValidators[] = $validator;
            }
        }

        $message = $this->mergeValidationMessages($failedValidators);

        if ($message->any()) {
            throw ValidationException::withMessages($message->toArray());
        }
    }

    /**
     * Merge validation messages from input validators.
     *
     * @param \Illuminate\Validation\Validator[] $validators
     *
     * @return MessageBag
     */
    protected function mergeValidationMessages($validators)
    {
        $messageBag = new MessageBag();

        foreach ($validators as $validator) {
            $messageBag = $messageBag->merge($validator->messages());
        }

        return $messageBag;
    }

    /**
     * @param string $class
     *
     * @return string
     */
    protected function resolveView($class)
    {
        $path = explode('\\', $class);

        $name = strtolower(array_pop($path));

        return "admin::actions.form.{$name}";
    }

    /**
     * @return string
     */
    public function getModalId()
    {
        if (!$this->modalId) {
            if ($this->action instanceof RowAction) {
                $this->modalId = uniqid('row-action-modal-').mt_rand(1000, 9999);
            } else {
                $this->modalId = strtolower(str_replace('\\', '-', get_class($this->action)));
            }
        }

        return $this->modalId;
    }

    /**
     * @param array $data
     *
     * @throws \Throwable
     *
     * @return mixed|string
     */
    public function addScript($data = [])
    {
        $this->action->attribute('modal', $this->getModalId());

        call_user_func(
            [$this->action, 'form'],
            ($this->action instanceof RowAction) ? $this->action->getRow() : null
        );

        $data = array_merge($data, [
            'rows'          => $this->rows,
            'modal_id'      => $this->getModalId(),
            'modal_size'    => $this->modalSize,
            'confirm'       => $this->confirm,
        ]);

        return Admin::view('admin::actions.form', $data);
    }
}
