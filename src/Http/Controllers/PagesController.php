<?php

namespace Encore\Admin\Http\Controllers;

use Encore\Admin\Layout\Content;
use Illuminate\Routing\Controller;

class PagesController extends Controller
{
    public function error404(Content $content)
    {
        return $content
            ->title('Error')
            ->description('404')
            ->view('admin::pages.404');
    }
}
