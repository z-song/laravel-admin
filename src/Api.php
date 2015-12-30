<?php

namespace Encore\Admin;

use Encore\Admin\Facades\Admin;

class Api {

    public function anyUsers()
    {
        Admin::grid(App\User::class, function(Admin\Grid $grid) {

            $grid->id('ID');
            $grid->username('用户名');
            $grid->email('邮箱')->attrs(['class' => 'email']);


            $grid->profile()->mobile('手机号')->value(function($mobile){
                return "+86$mobile";
            });
            $grid->profile()->avatar('头像')->value(function($avatar){
                return "<img src='$avatar' />";
            });

            $grid->created_at('创建时间')->sortable();
            $grid->updated_at('修改时间');

            $grid->column('profile.mobile', '手机号');
            $grid->column('profile.avatar', '头像');
            $grid->column('profile.address', '地址');

            $grid->tags('标签');

            $grid->actions('view|delete|update');

            $grid->model()->where('id', '>', 100)
                ->orderBy('id','asc')
                ->paginate(20);

            $grid->rows(function($row){
                if($row->id()->value % 2) {

                    $row->id()->style(['color' => '#f00']);
                    $row->attrs(['class' => 'highlight']);
                }
            });

        })->render();


        Admin::edit(App\User::class, function($edit){

            $edit->id('id', 'ID')->readonly();
            $edit->text('text', '用户名');
            $edit->email('email', '邮箱');
            $edit->password('password', '密码');
            $edit->hidden('hidden', '隐藏域');
            $edit->textarea('summary', '简介');
            $edit->editor('detail', '详细介绍');
            $edit->markdown('markdown', 'markdown文档');

            $edit->date('date', '日期')->rules('required');
            $edit->time('time', '时间');
            $edit->datetime('datetime', '日期时间');
            $edit->dateRange('start', 'end', '日期范围');
            $edit->dateTimeRange('start', 'end', '日期时间范围');

            $edit->ip('ip', 'ip地址')
                ->save(function($input){
                    return ip2long($input);
                })->show(function($value) {
                    return long2ip($value);
                });

            $edit->select('category', '分类')->options([]);
            $edit->multipleSelect('category', '分类')->options([]);

            $edit->radio('recommend', '是否推荐')->options([0 => '是', 3=> '否']);
            $edit->checkbox('category')->value([0, 1]);
            $edit->checkboxGroup('category')->options([0 => '是', 2=> '否']);
            $edit->color('color', '颜色');

            $edit->file('document', '附件')->upload();
            $edit->image('picture')->upload()->size()->preview();

            $edit->map('lan','lon','位置')->driver('baidu');
            $edit->json('setting', '配置');

            $edit->submit('提交');
            $edit->reset('撤销');
        });
    }

}