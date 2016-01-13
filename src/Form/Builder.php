<?php

namespace Encore\Admin\Form;

use Encore\Admin\Form;
use Encore\Admin\Form\Field;
use Illuminate\Support\Collection;

class Builder
{
    protected $id;

    protected $form;

    protected $fields;

    protected $options = ['title' => 'edit'];

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
        if(empty($options)) {
            return $this->options;
        }

        $this->options = array_merge($this->options, $options);
    }

    public function open($options = [])
    {
        if($this->mode == self::MODE_EDIT) {

            $attributes['action'] = $this->form->resource() . '/' . $this->id;
            $attributes['method'] = array_get($options, 'method', 'post');
            $attributes['accept-charset'] = 'UTF-8';
            $attributes['enctype'] = 'multipart/form-data';

            $this->form->hidden('_method')->value('PUT');
        }

        if($this->mode == self::MODE_CREATE) {

            $attributes['action'] = $this->form->resource();
            $attributes['method'] = array_get($options, 'method', 'post');
            $attributes['accept-charset'] = 'UTF-8';
            $attributes['enctype'] = 'multipart/form-data';
        }

        $attributes['class'] = array_get($options, 'class');

        foreach($attributes as $name => $value) {
            $html[] = "$name=\"$value\"";
        }

        return '<form '.join(' ', $html).'>';
    }

    public function close()
    {
        return '</form>';
    }

    public function submit()
    {
        if($this->mode == self::MODE_VIEW) {
            return;
        }

        return '<button type="submit" class="btn btn-info pull-right">提交</button>';
    }

    public function back()
    {
        return '<a href="'.$this->form->resource().'" class="btn btn-default">返回列表</a>';
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