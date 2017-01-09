<?php

namespace Encore\Admin\Form;

use Encore\Admin\Form;
use Illuminate\Support\Collection;

class Tab
{
    protected $form;

    protected $tabs;

    protected $edit;

    protected $counter = 0;

    public function __construct(Form $form)
    {
        $this->form = $form;

        $this->tabs = new Collection();
    }

    public function tab($title, \Closure $content)
    {
        $id = 'form-tab-'.(++$this->counter);

        $this->tabs->push(compact('id', 'title', 'content'));

        return $this;
    }

    public function getTabs()
    {
        return $this->tabs;
    }

    public function edit($id)
    {
        $this->edit = $id;
    }

    public function getEdit()
    {
        return $this->edit;
    }

    public function render()
    {
        dd($this->tabs);

        return view('admin::form.tab');
    }
}