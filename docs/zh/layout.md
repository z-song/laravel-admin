# 布局

`laravel-admin`的布局可参考后台首页的布局文件[HomeController.php](/src/Commands/stubs/ExampleController.stub)的`index()`方法。

`Encore\Admin\Layout\Content`类用来实现内容区的布局。`Content::row($element)`方法用来添加行元素：

```php
// 添加文字
$content->row('一行文字');

// 添加组件
$content->row(new Box('title', 'xxxx'));


```

`Encore\Admin\Layout\Row`类用于行内元素的布局。`Row::column($width, $element)`方法用来在行内添加列:

```php
// 行内添加列
$content->row(function(Row $row) {
    $row->column(4, 'xxx');
    $row->column(4, 'xxx');
    $row->column(4, 'xxx');
});

```
`$width`参数用来确定列元素的宽度，基于`bootstrap`布局定义的网格系统，取值为1-12的整数，12为屏幕的100%宽度。`$element`参数可以是任何实现了`Illuminate\Contracts\Support\Renderable`接口的对象或者其他可打印变量。
