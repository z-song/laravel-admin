<?php

namespace Encore\Admin\Form;

use Encore\Admin\Form;
use Encore\Admin\Form\Field;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Lang;

class Builder
{
    protected $id;

    protected $form;

    protected $fields;

    protected $options = ['title' => 'Edit'];

    const MODE_VIEW     = 'view';
    const MODE_EDIT     = 'edit';
    const MODE_CREATE   = 'create';

    /**
     * Form action mode, could be create|view|edit.
     *
     * @var string
     */
    protected $mode = 'create';


    public function __construct(Form $form)
    {
        $this->form = $form;

        $this->fields = new Collection();
    }

    public function setMode($mode = 'create')
    {
        $this->mode = $mode;
    }

    public function setResourceId($id)
    {
        $this->id = $id;
    }

    public function fields()
    {
        return $this->fields;
    }

    public function options($options = [])
    {
        if (empty($options)) {
            return $this->options;
        }

        $this->options = array_merge($this->options, $options);
    }

    public function title()
    {
        if ($this->mode == static::MODE_CREATE) {
            return Lang::get('admin::lang.create');
        }

        if ($this->mode == static::MODE_EDIT) {
            return Lang::get('admin::lang.edit');
        }

        if ($this->mode == static::MODE_VIEW) {
            return Lang::get('admin::lang.view');
        }
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
     * @return string
     */
    public function open($options = [])
    {
        if ($this->mode == self::MODE_EDIT) {
            $attributes['action'] = $this->form->resource() . '/' . $this->id;
            $this->form->hidden('_method')->value('PUT');
        }

        if ($this->mode == self::MODE_CREATE) {
            $attributes['action'] = $this->form->resource();
        }

        $attributes['method'] = array_get($options, 'method', 'post');
        $attributes['accept-charset'] = 'UTF-8';

        $attributes['class'] = array_get($options, 'class');

        if ($this->hasFile()) {
            $attributes['enctype'] = 'multipart/form-data';
        }

        foreach ($attributes as $name => $value) {
            $html[] = "$name=\"$value\"";
        }

        return '<form '.join(' ', $html).'>';
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

    public function submit()
    {
        if ($this->mode == self::MODE_VIEW) {
            return;
        }

        return '<button type="submit" class="btn btn-info pull-right">'.Lang::get('admin::lang.submit').'</button>';
    }

    public function back()
    {
        return '<a href="'.$this->form->resource().'" class="btn btn-default">'.
            Lang::get('admin::lang.back_to_list').'</a>';
    }

    public function build()
    {
        return view('admin::form', ['form' => $this])->render();
    }

    public function __toString()
    {
        return $this->build();
    }
}
