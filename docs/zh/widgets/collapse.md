# Collapse组件

`Encore\Admin\Widgets\Collapse`类用来生成折叠插件：
```php
use Encore\Admin\Widgets\Collapse;

$collapse = new Collapse();

$collapse->add('Bar', 'xxxxx');
$collapse->add('Orders', new Table());

echo $collapse->render();

```

`Collapse::add($title, $content)`方法用来给折叠组件添加一个折叠项，`$title`参数设置该折叠项的标题，`$content`参数用来舍子折叠区的内用。