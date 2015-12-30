<?php

namespace Encore\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

abstract class AdminController extends Controller
{
    abstract public function page();
    abstract public function form();

    public function index()
    {
        return $this->page()->render();

        //return $this->grid();
    }

    public function show($id)
    {
        return $this->form()->view($id);
    }

    public function edit($id)
    {
        return $this->form()->edit($id)->render();

        //return $this->form()->edit($id);
    }

    public function update($id, Request $request)
    {
        return $this->form()->update($id, $request->all());
    }

    public function destroy($id)
    {
        return $this->form()->destroy($id);
    }

    public function store()
    {
        return $this->form()->create();
    }

    public function create()
    {
        return $this->form()->render();
    }
}