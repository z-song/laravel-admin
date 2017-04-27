# Carousel组件

`Encore\Admin\Widgets\Carousel`用来生成滑动相册组件：

```php
use Encore\Admin\Widgets\Carousel;

$items = [
    [
        'image' => 'http://xxxx/xxx.jpg',
        'caption' => 'xxxx',
    ],
    [
        'image' => 'http://xxxx/xxx.jpg',
        'caption' => 'xxxx',
    ],
    [
        'image' => 'http://xxxx/xxx.jpg',
        'caption' => 'xxxx',
    ],
];

$carousel = new Carousel($items);

echo $carousel->render();
```

`Carousel::__construct($items)`，`$items`参数设置滑动相册的内容元素。


