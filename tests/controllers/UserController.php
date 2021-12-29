<?php

namespace Tests\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Tests\Models\Tag;
use Tests\Models\User;

class UserController extends AdminController
{
    protected $title = 'Users';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User());

        $grid->id('ID')->sortable();

        $grid->username();
        $grid->email();
        $grid->mobile();
        $grid->full_name();
        $grid->avatar()->display(function ($avatar) {
            return "<img src='{$avatar}' />";
        });
        $grid->profile()->postcode('Post code');
        $grid->profile()->address();
        $grid->position('Position');
        $grid->column('profile.color');
        $grid->profile()->start_at('开始时间');
        $grid->profile()->end_at('结束时间');
        $grid->column('data->json->field', 'Json Field');

        $grid->column('column1_not_in_table')->display(function () {
            return 'full name:'.$this->full_name;
        });

        $grid->column('column2_not_in_table')->display(function () {
            return $this->email.'#'.$this->profile['color'];
        });

        $grid->tags()->display(function ($tags) {
            $tags = collect($tags)->map(function ($tag) {
                return "<code>{$tag['name']}</code>";
            })->toArray();

            return implode('', $tags);
        });

        $grid->created_at();
        $grid->updated_at();

        $grid->filter(function ($filter) {
            $filter->like('username');
            $filter->like('email');
            $filter->like('profile.postcode');
            $filter->between('profile.start_at')->datetime();
            $filter->between('profile.end_at')->datetime();
        });

        $grid->actions(function ($actions) {
            if ($actions->getKey() % 2 == 0) {
                $actions->append('<a href="/" class="btn btn-xs btn-danger">detail</a>');
            }
        });

        return $grid;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        Form::extend('map', Form\Field\Map::class);
        Form::extend('editor', Form\Field\Editor::class);

        $form = new Form(new User());

        $form->display('id', 'ID');
        $form->text('username');
        $form->email('email')->rules('required');
        $form->mobile('mobile');
        $form->image('avatar')->help('上传头像', 'fa-image');
        $form->ignore(['password_confirmation']);
        $form->password('password')->rules('confirmed');
        $form->password('password_confirmation');

        $form->divider();

        $form->text('profile.first_name');
        $form->text('profile.last_name');
        $form->text('profile.postcode')->help('Please input your postcode');
        $form->textarea('profile.address')->rows(15);
        $form->map('profile.latitude', 'profile.longitude', 'Position');
        $form->color('profile.color');
        $form->datetime('profile.start_at');
        $form->datetime('profile.end_at');

        $form->multipleSelect('tags', 'Tags')->options(Tag::all()->pluck('name', 'id')); //->rules('max:10|min:3');

        $form->display('created_at', 'Created At');
        $form->display('updated_at', 'Updated At');

        $form->html('<a html-field>html...</a>');

        return $form;
    }
}
