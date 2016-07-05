<?php

namespace Encore\Admin\Controllers;

trait AdminController
{
    public function show($id)
    {
        return $this->edit($id);
    }

    public function update($id)
    {
        return $this->form()->update($id);
    }

    public function destroy($id)
    {
        if ($this->form()->destroy($id)) {
            return response()->json(['msg' => 'delete success!']);
        }
    }

    public function store()
    {
        return $this->form()->store();
    }
}
