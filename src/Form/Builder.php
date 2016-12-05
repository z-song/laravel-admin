<?php

namespace Encore\Admin\Form;

use Encore\Admin\Admin;
use Encore\Admin\Form;
use Illuminate\Support\Collection;

/**
 * Class Builder.
 */
class Builder
{
    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var Form
     */
    protected $form;

    /**
     * @var Collection
     */
    protected $fields;

    /**
     * @var array
     */
    protected $options = ['title' => 'Edit'];

    /**
     * Modes constants.
     */
    const MODE_VIEW = 'view';
    const MODE_EDIT = 'edit';
    const MODE_CREATE = 'create';

    /**
     * Form action mode, could be create|view|edit.
     *
     * @var string
     */
    protected $mode = 'create';

    /**
     * Allow delete item in form page.
     *
     * @var bool
     */
    protected $allowDeletion = true;

    /**
     * Builder constructor.
     *
     * @param Form $form
     */
    public function __construct(Form $form)
    {
        $this->form = $form;

        $this->fields = new Collection();
    }

    /**
     * Set the builder mode.
     *
     * @param string $mode
     *
     * @return void
     */
    public function setMode($mode = 'create')
    {
        $this->mode = $mode;
    }

    /**
     * Returns builder is $mode.
     *
     * @param $mode
     *
     * @return bool
     */
    public function isMode($mode)
    {
        return $this->mode == $mode;
    }

    /**
     * Set resource Id.
     *
     * @param $id
     *
     * @return void
     */
    public function setResourceId($id)
    {
        $this->id = $id;
    }

    /**
     * Get fields of this builder.
     *
     * @return Collection
     */
    public function fields()
    {
        return $this->fields;
    }

    /**
     * Add or get options.
     *
     * @param array $options
     *
     * @return array|void
     */
    public function options($options = [])
    {
        if (empty($options)) {
            return $this->options;
        }

        $this->options = array_merge($this->options, $options);
    }

    /**
     * @return string
     */
    public function title()
    {
        if ($this->mode == static::MODE_CREATE) {
            return trans('admin::lang.create');
        }

        if ($this->mode == static::MODE_EDIT) {
            return trans('admin::lang.edit');
        }

        if ($this->mode == static::MODE_VIEW) {
            return trans('admin::lang.view');
        }

        return '';
    }

    /**
     * Disable deletion in form page.
     *
     * @return $this
     */
    public function disableDeletion()
    {
        $this->allowDeletion = false;

        return $this;
    }

    /**
     * If allow deletion in form page.
     *
     * @return bool
     */
    public function allowDeletion()
    {
        return $this->allowDeletion;
    }

    /**
     * Determine if form fields has files.
     *
     * @return bool
     */
    public function hasFile()
    {
        foreach ($this->fields() as $field) {
            if ($field instanceof Field\File) {
                return true;
            }
        }

        return false;
    }

    /**
     * Open up a new HTML form.
     *
     * @param array $options
     *
     * @return string
     */
    public function open($options = [])
    {
        $attributes = [];

        if ($this->mode == self::MODE_EDIT) {
            $attributes['action'] = $this->form->resource().'/'.$this->id;
            $this->form->hidden('_method')->value('PUT');
        }

        if ($this->mode == self::MODE_CREATE) {
            $attributes['action'] = $this->form->resource(-1);
        }

        $attributes['method'] = array_get($options, 'method', 'post');
        $attributes['accept-charset'] = 'UTF-8';

        $attributes['class'] = array_get($options, 'class');

        if ($this->hasFile()) {
            $attributes['enctype'] = 'multipart/form-data';
        }

        $html = [];
        foreach ($attributes as $name => $value) {
            $html[] = "$name=\"$value\"";
        }

        return '<form '.implode(' ', $html).' pjax-container>';
    }

    /**
     * Close the current form.
     *
     * @return string
     */
    public function close()
    {
        $this->form = null;
        $this->fields = null;

        return '</form>';
    }

    /**
     * Build submit button.
     *
     * @return string|void
     */
    public function submit()
    {
        if ($this->mode == self::MODE_VIEW) {
            return;
        }

        return '<button type="submit" class="btn btn-info pull-right">'.trans('admin::lang.submit').'</button>';
    }

    /**
     * Render form.
     *
     * @return string
     */
    public function render()
    {
        $confirm = trans('admin::lang.delete_confirm');
        $token = csrf_token();

        $slice = $this->mode == static::MODE_CREATE ? -1 : -2;

        $location = '/'.trim($this->form->resource($slice), '/');

        $script = <<<SCRIPT
            $('.item_delete').click(function() {
                var id = $(this).data('id');
                if(confirm('{$confirm}')) {
                    $.post('{$this->form->resource($slice)}/' + id, {_method:'delete','_token':'{$token}'}, function(data){
                        $.pjax({
                            timeout: 2000,
                            url: '$location',
                            container: '#pjax-container'
                          });
                        return false;
                    });
                }
            });
SCRIPT;

        Admin::script($script);

        $vars = [
            'id'       => $this->id,
            'form'     => $this,
            'resource' => $this->form->resource($slice),
        ];

        if ($this->mode == static::MODE_CREATE) {
            $this->disableDeletion();
        }

        return view('admin::form', $vars)->render();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}
