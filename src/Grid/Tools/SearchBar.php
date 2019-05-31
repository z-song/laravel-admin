<?php

namespace Encore\Admin\Grid\Tools;

use Encore\Admin\Grid\Concerns\HasSearchBar;
use Illuminate\Support\Arr;

class SearchBar extends AbstractTool
{
    /**
     * @var string
     */
    protected $view = 'admin::grid.search-bar';

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render()
    {
        $query = request()->query();

        Arr::forget($query, HasSearchBar::$searchKey);

        $vars = [
            'action' => request()->url() . '?' . http_build_query($query),
            'key'    => HasSearchBar::$searchKey,
            'value'  => request(HasSearchBar::$searchKey),
        ];

        return view($this->view, $vars);
    }
}