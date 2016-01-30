<?php

namespace Encore\Admin\Controllers;

use Encore\Admin\Grid;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

abstract class AdminController extends Controller
{
    protected $title = 'Manage';

    protected $description = 'Optional description';

    abstract public function page();

    public function index()
    {
        $page = $this->page();

        if($page instanceof Grid) {
            $page->with($this->variables());
        }

        return $page->render();
    }

    public function show($id)
    {
        return $this->form()->view($id);
    }

    public function edit($id)
    {
        return $this->form()->edit($id)->render();
    }

    public function update($id, Request $request)
    {
        return $this->form()->update($id, $request->all());
    }

    public function destroy($id)
    {
        if($this->form()->destroy($id)) {
            return response()->json(['msg' => 'delete success!']);
        }
    }

    public function store()
    {
        return $this->form()->create();
    }

    public function create()
    {
        return $this->form()->render();
    }

    protected function variables()
    {
        return [
            'title' => $this->title,
            'description' => $this->description
        ];
    }
}