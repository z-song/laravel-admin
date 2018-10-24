<?php

namespace Tests\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Form;
use Encore\Admin\Layout\Content;
use Tests\Models\Person;

class PersonController extends Controller
{
    use ModelForm;

    /**
     * Edit interface.
     *
     * @param string  $id
     * @param Content $content
     *
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Person')
            ->description()
            ->row($this->form()->edit($id));
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        Form::extend('select', Form\Field\Select::class);

        $form = new Form(new Person());
        $form->text('name');
        $form->select('country_id')->options('admin/api/countries')->load('city_id', 'admin/api/cities');
        $form->select('city_id');

        return $form;
    }
}
