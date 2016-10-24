# Box组件

`Encore\Admin\Widgets\Box`用来生成box组件：

```php
use Encore\Admin\Widgets\Box;

$box = new Box('Box标题', 'Box内容');

$box->removable();

$box->collapsable();

$box->style('info');

$box->solid();

echo $box;

```

`Box::__construct($title, $content)`,`$title`参数为Box组件的标题，`$content`参数为Box的内容元素，可以是实现了`Illuminate\Contracts\Support\Renderable`接口的对象或者其他可打印变量。

`Box::title($title)`方法用来设置Box组件标题。

`Box::content($content)`方法用来设置Box组件的内容元素。

`Box::removable()`方法将Box组件设置为可关闭。

`Box::collapsable()`方法将Box组件设置为可展开和收起。

`Box::style($style)`方法设置Box组件的样式，可填值为`primary`,`info`,`danger`,`warning`,`success`,`default`。

`Box::solid()`方法为Box组件添加边框。


