<?php

namespace Encore\Admin\Http\Controllers;

trait HasResourceActions
{
    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        return $this->form()->update($id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return mixed
     */
    public function store()
    {
        return $this->form()->create()->store();
    }

    /**
     * Destroy record.
     *
     * @param $id
     */
    public function destroy($id)
    {
        return app(HandleController::class)->handleAction(request());
    }
}
