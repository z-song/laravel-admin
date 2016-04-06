<?php

namespace Encore\Admin\Controllers;

use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function show($id)
    {
        return redirect($this->edit($id));

        return $this->form()->view($id);
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

    public function create()
    {
        return $this->form();
    }
}
