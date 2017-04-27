# Box

`Encore\Admin\Widgets\Box` used to generate box components:

```php
use Encore\Admin\Widgets\Box;

$box = new Box('Box Title', 'Box content');

$box->removable();

$box->collapsable();

$box->style('info');

$box->solid();

echo $box;

```

`Box::__construct($title, $content)`,`$title`参数为Box组件的标题，`$content`参数为Box的内容元素，可以是实现了`Illuminate\Contracts\Support\Renderable`接口的对象或者其他可打印变量。

The `$content` parameter is the content element of the Box, which can be either an implementation of the `Illuminate\Contracts \ Support\Renderable` interface, or other printable variables.

The `Box::title($title)` method is used to set the Box component title.

The `Box::content($content)` method is used to set the content element of a Box component.

The `Box::removable()` method sets the Box component as removable.

The `Box::collapsable()` method sets the Box component as collapsable.

`Box::style($style)` method sets the style of the Box component to fill in `primary`,` info`, `danger`,` warning`, `success`,` default`.

The `Box::solid()` method adds a border to the Box component.


