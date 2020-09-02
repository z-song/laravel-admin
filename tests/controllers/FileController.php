<?php

namespace Tests\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Http\Controllers\AdminController;
use Encore\Admin\Table;
use Tests\Models\File;

class FileController extends AdminController
{
    protected $title = 'Files';

    /**
     * Make a table builder.
     *
     * @return Table
     */
    protected function table()
    {
        $table = new Table(new File());

        $table->id('ID')->sortable();

        $table->created_at();
        $table->updated_at();

        return $table;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new File());

        $form->display('id', 'ID');

        $form->file('file1');
        $form->file('file2');
        $form->file('file3');
        $form->file('file4');
        $form->file('file5');
        $form->file('file6');

        $form->display('created_at', 'Created At');
        $form->display('updated_at', 'Updated At');

        return $form;
    }
}
