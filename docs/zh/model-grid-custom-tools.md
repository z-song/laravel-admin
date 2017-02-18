# 自定义工具

在`model-grid`的头部默认有`批量删除`和`刷新`两个操作工具，如果有更多的操作需求，`model-grid`提供了自定义工具的功能,下面的示例添加一个性别分类选择的按钮组工具。

先定义工具类`app/Admin/Extensions/Tools/UserGender.php`：

```php
<?php

namespace App\Admin\Extensions\Tools;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\AbstractTool;
use Illuminate\Support\Facades\Request;

class UserGender extends AbstractTool
{
    protected function script()
    {
        $url = Request::fullUrlWithQuery(['gender' => '_gender_']);

        return <<<EOT
    
$('input:radio.user-gender').change(function () {

    var url = "$url".replace('_gender_', $(this).val());

    $.pjax({container:'#pjax-container', url: url });

});

EOT;
    }

    public function render()
    {
        Admin::script($this->script());

        $options = [
            'all'   => 'All',
            'm'     => 'Male',
            'f'     => 'Female',
        ];

        return view('admin.tools.gender', compact('options'));
    }
}

```
视图`admin.tools.gender`文件为`resources/views/admin/tools/gender.blade.php`:
```php
<div class="btn-group" data-toggle="buttons">
    @foreach($options as $option => $label)
    <label class="btn btn-default btn-sm {{ \Request::get('gender', 'all') == $option ? 'active' : '' }}">
        <input type="radio" class="user-gender" value="{{ $option }}">{{$label}}
    </label>
    @endforeach
</div>
```

在`model-grid`引入这个工具：
```php

$grid->tools(function ($tools) {
    $tools->append(new UserGender());
});

```

在`model-grid`定义中接收到`gender`参数后，做好数据查询就可以了：
```php
if (in_array(Request::get('gender'), ['m', 'f'])) {
    $grid->model()->where('gender', Request::get('gender'));
}
```

可以参考上面的方式来添加自己的工具。

## 批量操作

目前默认实现了批量删除操作的功能，如果要关掉批量删除操作：
```php
$grid->tools(function ($tools) {
    $tools->batch(function ($batch) {
        $batch->disableDelete();
    });
});

```

如果要添加自定义的批量操作，可以参考下面的例子。

下面是扩展一个对文章批量发布的功能：

先定义操作类`app/Admin/Extensions/Tools/ReleasePost.php`：
```php
<?php

namespace App\Admin\Extensions\Tools;

use Encore\Admin\Grid\Tools\BatchAction;

class ReleasePost extends BatchAction
{
    protected $action;

    public function __construct($action = 1)
    {
        $this->action = $action;
    }
    
    public function script()
    {
        return <<<EOT
        
$('{$this->getElementClass()}').on('click', function() {

    $.ajax({
        method: 'post',
        url: '/{$this->resource}/release',
        data: {
            _token:'{$this->getToken()}',
            ids: selectedRows(),
            action: {$this->action}
        },
        success: function () {
            $.pjax.reload('#pjax-container');
            toastr.success('操作成功');
        }
    });
});

EOT;

    }
}
```
看代码的实现，通过click操作发送一个post请求，把选中的行数据`id`通过数组的形式传给后端接口，后端接口拿到`id`列表和要修改的状态来更新数据，然后前端刷新页面(pjax reload)，并弹出`toastr`提示操作成功。

在`model-grid`中加入这个批量操作功能：
```php
$grid->tools(function ($tools) {
    $tools->batch(function ($batch) {
        $batch->add('发布文章', new ReleasePost(1));
        $batch->add('文章下线', new ReleasePost(0));
    });
});
```

这样批量操作下拉按钮下面就会添加两个操作项，最后一步就是添加一个接口来处理批量操作的请求了，接口代码如下：
```php

class PostController extends Controller
{
    ...
    
    public function release(Request $request)
    {
        foreach (Post::find($request->get('ids')) as $post) {
            $post->released = $request->get('action');
            $post->save();
        }
    }
    
    ...
}
```

然后添加路由指向上面的接口：
```php
$router->post('posts/release', 'PostController@release');
```

这样整个流程就完成了。

## 自定义导出

`model-grid`默认只有简单的csv文件的导出功能,如果想要控制导出格式或导出字段,可以用下面的方式自定义导出功能

新建导出类， 比如`app/Admin/Extensions/CustomExporter.php`：

```php
<?php

namespace App\Admin\Extensions;

use Encore\Admin\Grid\Exporters\AbstractExporter;

class CustomExporter extends AbstractExporter
{
    public function export()
    {
        $filename = $this->getTable().'.csv';

        // 这里获取数据
        dd($this->getData());

        // 根据上面的数据拼接出导出数据，
        $output = '';

        // 在这里控制你想输出的格式,或者使用第三方库导出Excel文件
        $headers = [
            'Content-Encoding'    => 'UTF-8',
            'Content-Type'        => 'text/csv;charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        // 导出文件，
        response(rtrim($output, "\n"), 200, $headers)->send();

        exit;
    }
}
```

然后在grid中使用：
```php
use App\Admin\Extensions\CustomExporter;

...

$grid->exporter(new CustomExporter());
```
就好了，导出类的逻辑可参考 [CsvExporter.php](https://github.com/z-song/laravel-admin/blob/1.3/src/Grid/Exporters/CsvExporter.php)