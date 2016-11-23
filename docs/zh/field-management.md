# 组件管理

> 目前暂时只支持Larave 5.3

## 移除已有组件

form表单内置的`map`和`editor`组件通过cdn的方式引用了前端文件，如果网络方面有问题，可以通过下面的方式将它们移除

找到文件`app/Admin/bootstrap.php`,如果文件不存在，请更新`laravel-admin`，然后新建该文件

```php

<?php

use Encore\Admin\Form;

Form::forget('map');
Form::forget('editor');

// or

Form::forget(['map', 'editor']);

```

这样就去掉了这两个组件，可以通过该方式去掉其它组件。


## 扩展自定义组件

通过下面的步骤来扩展一个基于[codemirror](http://codemirror.net/index.html)的PHP代码编辑器，效果参考[PHP mode](http://codemirror.net/mode/php/)。

先将[codemirror](http://codemirror.net/codemirror.zip)库下载并解压到前端资源目录下，比如放在`public/packages/codemirror-5.20.2`目录下。

新建组件类`app/Admin/Extensions/PHPEditor.php`:

```php
<?php

namespace App\Admin\Extensions;

use Encore\Admin\Form\Field;

class PHPEditor extends Field
{
    protected $view = 'admin.php-editor';

    protected static $css = [
        '/packages/codemirror-5.20.2/lib/codemirror.css',
    ];

    protected static $js = [
        '/packages/codemirror-5.20.2/lib/codemirror.js',
        '/packages/codemirror-5.20.2/addon/edit/matchbrackets.js',
        '/packages/codemirror-5.20.2/mode/htmlmixed/htmlmixed.js',
        '/packages/codemirror-5.20.2/mode/xml/xml.js',
        '/packages/codemirror-5.20.2/mode/javascript/javascript.js',
        '/packages/codemirror-5.20.2/mode/css/css.js',
        '/packages/codemirror-5.20.2/mode/clike/clike.js',
        '/packages/codemirror-5.20.2/mode/php/php.js',
    ];

    public function render()
    {
        $this->script = <<<EOT

CodeMirror.fromTextArea(document.getElementById("{$this->id}"), {
    lineNumbers: true,
    mode: "text/x-php",
    extraKeys: {
        "Tab": function(cm){
            cm.replaceSelection("    " , "end");
        }
     }
});

EOT;
        return parent::render();

    }
}

```

>类中的静态资源也同样可以从外部引入，参考[Editor.php](https://github.com/z-song/laravel-admin/blob/1.3/src/Form/Field/Editor.php)

创建视图`resources/views/admin/php-editor.blade.php`:

```php

<div class="form-group {!! !$errors->has($label) ?: 'has-error' !!}">

    <label for="{{$id}}" class="col-sm-2 control-label">{{$label}}</label>

    <div class="col-sm-6">

        @include('admin::form.error')

        <textarea class="form-control" id="{{$id}}" name="{{$name}}" placeholder="{{ trans('admin::lang.input') }} {{$label}}" {!! $attributes !!} >{{ old($column, $value) }}</textarea>
    </div>
</div>

```

最后找到文件`app/Admin/bootstrap.php`,如果文件不存在，请更新`laravel-admin`，然后新建该文件,添加下面代码：

```
<?php

use App\Admin\Extensions\PHPEditor;
use Encore\Admin\Form;

Form::extend('php', PHPEditor::class);

```

这样就能在[model-form](model-form.md)中使用PHP编辑器了：

```

$form->php('code');

```

通过这种方式，可以添加任意你想要添加的form组件。

### 集成富文本编辑器wangEditor

[wangEditor](http://www.wangeditor.com/)是一个优秀的国产的轻量级富文本编辑器，如果`laravel-admin`自带的基于`ckeditor`的编辑器组件使用上有问题，可以通过下面的步骤可以集成它，并覆盖掉`ckeditor`：

先下载前端库文件[wangEditor](https://github.com/wangfupeng1988/wangEditor/releases)，解压到目录`public/packages/wangEditor-2.1.22`。

然后新建组件类`app/Admin/Extensions/WangEditor.php`。

```php

<?php

namespace App\Admin\Extensions;

use Encore\Admin\Form\Field;

class WangEditor extends Field
{
    protected $view = 'admin::form.editor';

    protected static $css = [
        '/packages/wangEditor-2.1.22/dist/css/wangEditor.min.css',
    ];

    protected static $js = [
        '/packages/wangEditor-2.1.22/dist/js/wangEditor.min.js',
    ];

    public function render()
    {
        $this->script = <<<EOT

var editor = new wangEditor('{$this->id}');
    editor.create();

EOT;
        return parent::render();

    }
}

```

然后注册进`laravel-admin`,在`app/Admin/bootstrap.php`中添加以下代码：

```php

<?php

use App\Admin\Extensions\WangEditor;
use Encore\Admin\Form;

Form::extend('editor', WangEditor::class);

```

调用:

```

$form->editor('content');

```

> 组件类中指定了`admin::form.editor`作为视图文件，视图文件路径在`vendor/encore/laravel-admin/views/form/editor.blade.php`，如果需要修改视图文件，可以将上述视图文件拷贝到`resources/views`目录下自行修改，然后在组件类`app/Admin/Extensions/WangEditor.php`的`$view`属性指定刚才修改的view即可。
