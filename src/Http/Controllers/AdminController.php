<?php

namespace Encore\Admin\Http\Controllers;

use Encore\Admin\Admin;
use Encore\Admin\Form;
use Encore\Admin\Layout\Content;
use Illuminate\Routing\Controller;
use Symfony\Component\DomCrawler\Crawler;

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
        // 'index'  => 'Index',
        // 'show'   => 'Show',
        // 'edit'   => 'Edit',
        // 'create' => 'Create',
    ];

    /**
     * Get content title.
     *
     * @return string
     */
    public function title()
    {
        return $this->title;
    }

    /**
     * @return array
     */
    public function descripttion()
    {
        return $this->description;
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
            ->body($this->table());
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
        $content
            ->title($this->title())
            ->description($this->description['edit'] ?? trans('admin.edit'));

        return $this->renderModalForm($this->form()->edit($id), $content);
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
        $content
            ->title($this->title())
            ->description($this->description['create'] ?? trans('admin.create'));

        return $this->renderModalForm($this->form()->create(), $content);
    }

    /**
     * @param Form    $form
     * @param Content $content
     *
     * @return mixed
     */
    public function renderModalForm($form, $content)
    {
        if (!request()->has('_modal')) {
            return $content->body($form);
        }

        $crawler = new Crawler(
            $form->disableTools()->disableFooterCheck()->render()
        );

        return implode("\r\n", [
            $crawler->filter('form')->outerHtml(),
            Admin::style(),
            Admin::html(),
            Admin::script(),
        ]);
    }
}
