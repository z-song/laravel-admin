# Components management


## Remove components

The built-in `map` and `editor` components requires the front-end files via cdn, and if there are problems with the network, they can be removed in the following ways

Locate the file `app/Admin/bootstrap.php`. If the file does not exist, update `laravel-admin` and create this file.

```php

<?php

use Encore\Admin\Form;

Form::forget('map');
Form::forget('editor');

// or

Form::forget(['map', 'editor']);

```

This removes the two components, which can be used to remove the other components.

## Extend the custom component

Extend a PHP code editor based on [codemirror](http://codemirror.net/index.html) with the following steps.

see [PHP mode](http://codemirror.net/mode/php/).

Download and unzip the [codemirror](http://codemirror.net/codemirror.zip) library to the front-end resource directory, for example, in the directory `public/packages/codemirror-5.20.2`.

Create a new component class `app/Admin/Extensions/PHPEditor.php`:

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

>Static resources in the class can also be imported from outside, see [Editor.php](https://github.com/z-song/laravel-admin/blob/1.3/src/Form/Field/Editor.php)

Create a view file `resources/views/admin/php-editor.blade.php`:

```php

<div class="form-group {!! !$errors->has($label) ?: 'has-error' !!}">

    <label for="{{$id}}" class="col-sm-2 control-label">{{$label}}</label>

    <div class="col-sm-6">

        @include('admin::form.error')

        <textarea class="form-control" id="{{$id}}" name="{{$name}}" placeholder="{{ trans('admin::lang.input') }} {{$label}}" {!! $attributes !!} >{{ old($column, $value) }}</textarea>
    </div>
</div>

```

Finally, find the file `app/Admin/bootstrap.php`, if the file does not exist, update `laravel-admin`, and then create this file, add the following code:

```
<?php

use App\Admin\Extensions\PHPEditor;
use Encore\Admin\Form;

Form::extend('php', PHPEditor::class);

```

And then you can use PHP editor in [model-form](model-form.md):

```

$form->php('code');

```

In this way, you can add any form components you want to add.