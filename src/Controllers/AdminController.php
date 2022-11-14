<?php

namespace Encore\Admin\Controllers;

use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Illuminate\Routing\Controller;

class AdminController extends Controller
{
    use HasResourceActions;

    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Title';

    /**
     * Set description for following 4 action pages.
     *
     * @var array
     */
    protected $description = [
        //        'index'  => 'Index',
        //        'show'   => 'Show',
        //        'edit'   => 'Edit',
        //        'create' => 'Create',
    ];

    /**
     * ID of editing element
     */
    protected $editingId;

    /**
     * Get content title.
     *
     * @return string
     */
    protected function title()
    {
        return $this->title;
    }

    /**
     * Index interface.
     *
     * @param Content $content
     *
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->title($this->title())
            ->description($this->description['index'] ?? trans('admin.list'))
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed   $id
     * @param Content $content
     *
     * @return Content
     */
    public function show($id, Content $content)
    {
        $routeParameters = request()->route()->parameters();
        $id = is_null($this->routeParamName) ? end($routeParameters) : request()->route($this->routeParamName);
        $this->editingId = $id;
        return $content
            ->title($this->title())
            ->description($this->description['show'] ?? trans('admin.show'))
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed   $id
     * @param Content $content
     *
     * @return Content
     */
    public function edit($id, Content $content)
    {
        $routeParameters = request()->route()->parameters();
        $id = is_null($this->routeParamName) ? end($routeParameters) : request()->route($this->routeParamName);
        $this->editingId = $id;
        return $content
            ->title($this->title())
            ->description($this->description['edit'] ?? trans('admin.edit'))
            ->row(function (Row $row) use (&$id) {
                $row->column(2, '');
                $row->column(8, $this->form()->edit($id));
            });
    }

    /**
     * Create interface.
     *
     * @param Content $content
     *
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->title($this->title())
            ->description($this->description['create'] ?? trans('admin.create'))
            ->row(function (Row $row) {
                $row->column(2, '');
                $row->column(8, $this->form());
            });
    }
}
