<?php

namespace Tests\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Http\Controllers\AdminController;
use Encore\Admin\Table;
use Tests\Models\MultipleImage;

class MultipleImageController extends AdminController
{
    protected $title = 'Images';

    /**
     * Make a table builder.
     *
     * @return Table
     */
    protected function table()
    {
        $table = new Table(new MultipleImage());

        $table->id('ID')->sortable();

        $table->created_at();
        $table->updated_at();

        $table->disableFilter();

        return $table;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new MultipleImage());

        $form->display('id', 'ID');

        $form->multipleImage('pictures');

        $form->display('created_at', 'Created At');
        $form->display('updated_at', 'Updated At');

        return $form;
    }
}
